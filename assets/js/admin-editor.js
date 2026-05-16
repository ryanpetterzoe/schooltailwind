/**
 * Admin Rich Text Editor (Quill 2)
 * --------------------------------
 * Auto-converts every <textarea data-rich-editor> into a WYSIWYG editor
 * with a Word-like toolbar. The original textarea stays in the DOM
 * (hidden) so existing PHP form handling keeps working — we just sync
 * the HTML back before submit. No API key, no external service.
 *
 * IMAGE RESIZE/ALIGN
 * ------------------
 * Click an image to reveal a corner drag-handle and a floating toolbar
 * (L/C/R align + 25/50/100% preset width + delete). Quill 2's default
 * Image format only whitelists `alt`, `width`, `height`, so we register
 * a proper subclass that ALSO preserves `style` and `class` attributes.
 * The width/float survive the next text-change and round-trip through
 * the model. The hidden textarea is updated on every change so the
 * form submits the latest HTML.
 *
 * Usage in PHP:
 *   <textarea name="content" data-rich-editor></textarea>
 *
 * Optional attributes:
 *   data-editor-height="320"   custom editor body height in px
 *   data-editor-simple         simpler toolbar (no images/colors)
 */
(function () {
    if (typeof Quill === 'undefined') return;

    /* ============================================================
     * 1. ResizableImage blot
     * ------------------------------------------------------------
     * Proper ES6 subclass of Quill's built-in Image format that adds
     * `style`, `class`, `width`, `height` to the list of preserved
     * attributes. We override BOTH `static formats()` AND
     * `prototype.format()` so the round-trip works in both directions
     * (DOM -> model and model -> DOM).
     * ============================================================ */
    var ImageBase = Quill.import('formats/image');

    function ResizableImage() {
        ImageBase.apply(this, arguments);
    }
    // ES5-style inheritance because Quill 2's CDN build is ES2017
    // and we want to stay compatible with older browsers in admin.
    ResizableImage.prototype = Object.create(ImageBase.prototype);
    ResizableImage.prototype.constructor = ResizableImage;

    // Copy static members from the parent (blotName, tagName, etc.)
    Object.getOwnPropertyNames(ImageBase).forEach(function (k) {
        if (['length', 'name', 'prototype'].indexOf(k) !== -1) return;
        try { ResizableImage[k] = ImageBase[k]; } catch (e) {}
    });

    // Use OUR own list, not the parent's. Order matters for round-trip.
    ResizableImage.ATTRIBUTES = ['alt', 'height', 'width', 'style', 'class'];

    // DOM -> model: read the image's attributes into the format object.
    ResizableImage.formats = function (domNode) {
        return ResizableImage.ATTRIBUTES.reduce(function (acc, attr) {
            if (domNode.hasAttribute(attr)) {
                var val = domNode.getAttribute(attr);
                if (val !== null && val !== '') acc[attr] = val;
            }
            return acc;
        }, {});
    };

    // model -> DOM: write the attributes back onto the image element.
    ResizableImage.prototype.format = function (name, value) {
        if (ResizableImage.ATTRIBUTES.indexOf(name) > -1) {
            if (value) this.domNode.setAttribute(name, value);
            else this.domNode.removeAttribute(name);
        } else {
            ImageBase.prototype.format.call(this, name, value);
        }
    };

    // Replace Quill's built-in image format with our subclass.
    Quill.register(ResizableImage, true);
    Quill.register('formats/image', ResizableImage, true);

    /* ============================================================
     * 2. Toolbar presets
     * ============================================================ */
    var FULL_TOOLBAR = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: [] }],
        ['blockquote', 'link', 'image'],
        ['clean'],
    ];
    var SIMPLE_TOOLBAR = [
        [{ header: [2, 3, false] }],
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link', 'clean'],
    ];

    var instances = new WeakMap();

    /* ============================================================
     * 3. Build editor for one textarea
     * ============================================================ */
    function buildEditor(textarea) {
        if (textarea.dataset.richInitialized === '1') return;
        textarea.dataset.richInitialized = '1';

        var simple = textarea.hasAttribute('data-editor-simple');
        var height = parseInt(textarea.dataset.editorHeight, 10) || 320;
        var toolbar = simple ? SIMPLE_TOOLBAR : FULL_TOOLBAR;

        var wrap = document.createElement('div');
        wrap.className =
            'rich-editor-wrap rounded-xl border border-slate-200 dark:border-slate-700 ' +
            'bg-white dark:bg-slate-900 overflow-hidden';
        wrap.style.minHeight = (height + 44) + 'px';

        var editorDiv = document.createElement('div');
        editorDiv.className = 'rich-editor-body';
        editorDiv.style.minHeight = height + 'px';
        editorDiv.innerHTML = textarea.value || '';

        wrap.appendChild(editorDiv);
        textarea.style.display = 'none';
        textarea.parentNode.insertBefore(wrap, textarea.nextSibling);

        var quill = new Quill(editorDiv, {
            theme: 'snow',
            placeholder: textarea.placeholder || 'Tulis konten di sini...',
            modules: { toolbar: toolbar },
        });
        instances.set(textarea, quill);

        // Mirror to textarea on every text-change.
        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            textarea.value = html === '<p><br></p>' ? '' : html;
        });

        // Image resize/align tools.
        attachImageTools(quill, textarea);

        // Re-sync just before form submit (defence-in-depth).
        var form = textarea.form;
        if (form && !form.dataset.richSubmitHook) {
            form.dataset.richSubmitHook = '1';
            form.addEventListener('submit', function () {
                form.querySelectorAll('textarea[data-rich-editor]').forEach(function (ta) {
                    var q = instances.get(ta);
                    if (!q) return;
                    var h = q.root.innerHTML;
                    ta.value = h === '<p><br></p>' ? '' : h;
                });
            }, true);
        }
    }

    function init() {
        document.querySelectorAll('textarea[data-rich-editor]').forEach(buildEditor);
    }

    /* ============================================================
     * 4. IMAGE TOOLS — Word-style resize + alignment
     * ------------------------------------------------------------
     * - Click an image -> floating toolbar + corner drag-handle.
     * - Drag the handle to resize proportionally; width persists as
     *   inline `style="width: <pct>%"` AND `width="<pct>%"`.
     * - Toolbar presets: 25 / 50 / 100% + L / C / R align + delete.
     * - Alignment uses inline `style="float: …; margin: …;"` plus
     *   `data-align` for round-trip identification.
     * ============================================================ */
    function attachImageTools(quill, textarea) {
        var editorBody = quill.root;          // the .ql-editor div
        var current = null;                   // currently selected <img>
        var overlay = null;
        var dragging = false;
        var startX = 0, startW = 0;

        // Cache of size/style we last set per image, keyed by the image
        // element. Used by the MutationObserver below to restore the
        // attributes if Quill ever removes them during normalization.
        var savedAttrs = new WeakMap();

        function ensureOverlay() {
            if (overlay) return overlay;
            overlay = document.createElement('div');
            overlay.className = 'rich-image-overlay';
            overlay.innerHTML = ''
                + '<div class="rich-image-toolbar">'
                +   '<button type="button" data-action="align-left"   title="Rata Kiri"><i class="fas fa-align-left"></i></button>'
                +   '<button type="button" data-action="align-center" title="Rata Tengah"><i class="fas fa-align-center"></i></button>'
                +   '<button type="button" data-action="align-right"  title="Rata Kanan"><i class="fas fa-align-right"></i></button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="size-25"  title="Lebar 25%">25%</button>'
                +   '<button type="button" data-action="size-50"  title="Lebar 50%">50%</button>'
                +   '<button type="button" data-action="size-100" title="Lebar 100%">100%</button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="remove" title="Hapus gambar"><i class="fas fa-trash"></i></button>'
                + '</div>'
                + '<div class="rich-image-handle" title="Seret untuk ubah ukuran"></div>';
            document.body.appendChild(overlay);

            var bar = overlay.querySelector('.rich-image-toolbar');
            bar.addEventListener('mousedown', function (e) { e.preventDefault(); });
            bar.addEventListener('click', function (e) {
                var btn = e.target.closest('button[data-action]');
                if (!btn || !current) return;
                e.preventDefault();
                applyAction(btn.getAttribute('data-action'));
            });

            var handle = overlay.querySelector('.rich-image-handle');
            handle.addEventListener('mousedown', startResize);
            handle.addEventListener('touchstart', startResize, { passive: false });
            return overlay;
        }

        function applyToImage(img) {
            // Re-apply whatever attributes we have cached for this img.
            var s = savedAttrs.get(img);
            if (!s) return false;
            var changed = false;
            if (s.width != null && img.getAttribute('width') !== s.width) {
                img.setAttribute('width', s.width); changed = true;
            }
            if (s.style != null && img.getAttribute('style') !== s.style) {
                img.setAttribute('style', s.style); changed = true;
            }
            if (s.align != null && img.getAttribute('data-align') !== s.align) {
                img.setAttribute('data-align', s.align); changed = true;
            }
            return changed;
        }

        function snapshot(img) {
            savedAttrs.set(img, {
                width: img.getAttribute('width'),
                style: img.getAttribute('style'),
                align: img.getAttribute('data-align'),
            });
        }

        function applyAction(action) {
            if (!current) return;
            switch (action) {
                case 'align-left':
                    current.style.float = 'left';
                    current.style.display = '';
                    current.style.margin = '0.4em 1em 0.4em 0';
                    current.setAttribute('data-align', 'left');
                    break;
                case 'align-center':
                    current.style.float = 'none';
                    current.style.display = 'block';
                    current.style.margin = '0.6em auto';
                    current.setAttribute('data-align', 'center');
                    break;
                case 'align-right':
                    current.style.float = 'right';
                    current.style.display = '';
                    current.style.margin = '0.4em 0 0.4em 1em';
                    current.setAttribute('data-align', 'right');
                    break;
                case 'size-25':  setWidthPct(current, 25); break;
                case 'size-50':  setWidthPct(current, 50); break;
                case 'size-100': setWidthPct(current, 100); break;
                case 'remove':
                    current.parentNode && current.parentNode.removeChild(current);
                    hide();
                    syncTextarea();
                    return;
            }
            snapshot(current);
            position();
            syncTextarea();
        }

        function setWidthPct(img, pct) {
            img.setAttribute('width', pct + '%');
            img.style.width  = pct + '%';
            img.style.height = 'auto';
        }

        function syncTextarea() {
            if (!textarea) return;
            var html = quill.root.innerHTML;
            textarea.value = html === '<p><br></p>' ? '' : html;
        }

        function position() {
            if (!current || !overlay) return;
            var r = current.getBoundingClientRect();
            overlay.style.top    = (r.top  + window.scrollY) + 'px';
            overlay.style.left   = (r.left + window.scrollX) + 'px';
            overlay.style.width  = r.width  + 'px';
            overlay.style.height = r.height + 'px';
            overlay.style.display = 'block';
        }

        function show(img) { current = img; ensureOverlay(); position(); }
        function hide()    { current = null; if (overlay) overlay.style.display = 'none'; }

        function startResize(e) {
            if (!current) return;
            e.preventDefault();
            dragging = true;
            var p = e.touches ? e.touches[0] : e;
            startX = p.clientX;
            startW = current.getBoundingClientRect().width;

            document.addEventListener('mousemove', onResize);
            document.addEventListener('mouseup', endResize);
            document.addEventListener('touchmove', onResize, { passive: false });
            document.addEventListener('touchend', endResize);
        }
        function onResize(e) {
            if (!dragging || !current) return;
            e.preventDefault();
            var p = e.touches ? e.touches[0] : e;
            var dx = p.clientX - startX;
            var newW = Math.max(40, startW + dx);

            var bodyW = editorBody.clientWidth - 8;
            if (bodyW < 40) bodyW = 40;
            if (newW > bodyW) newW = bodyW;

            var pct = Math.round((newW / bodyW) * 100);
            pct = Math.max(10, Math.min(100, pct));
            setWidthPct(current, pct);
            snapshot(current);
            position();
        }
        function endResize() {
            if (!dragging) return;
            dragging = false;
            document.removeEventListener('mousemove', onResize);
            document.removeEventListener('mouseup', endResize);
            document.removeEventListener('touchmove', onResize);
            document.removeEventListener('touchend', endResize);
            syncTextarea();
        }

        // Click on an image -> select + show overlay.
        editorBody.addEventListener('click', function (e) {
            var img = e.target.closest('img');
            if (img && editorBody.contains(img)) {
                show(img);
                e.stopPropagation();
            } else {
                hide();
            }
        });

        // Click outside -> deselect.
        document.addEventListener('mousedown', function (e) {
            if (!current) return;
            if (overlay && overlay.contains(e.target)) return;
            if (e.target === current) return;
            hide();
        });
        window.addEventListener('scroll', function () { if (current) position(); }, true);
        window.addEventListener('resize', function () { if (current) position(); });

        /* --------------------------------------------------------
         * Defence-in-depth: MutationObserver
         *
         * Even with the ResizableImage subclass registered, certain
         * code paths in Quill (paste handling, undo/redo, format
         * commands triggered on neighbouring text) can replace the
         * <img> element with a freshly-built one that lost our
         * inline style. The observer:
         *
         *  - On attribute mutation (style/width changed): if the
         *    new value differs from the cached value, restore.
         *  - On childList mutation (img added): if the new img has
         *    an entry in savedAttrs (matched by `src`), re-apply.
         *
         * Together this guarantees the user's resize/align stays
         * visible even when Quill normalises the DOM under us.
         * -------------------------------------------------------- */
        var srcCache = {};   // src -> { width, style, align }
        function rememberBySrc(img) {
            var s = savedAttrs.get(img);
            if (s && img.src) srcCache[img.src] = s;
        }
        function recoverBySrc(img) {
            if (!img.src) return false;
            var s = srcCache[img.src];
            if (!s) return false;
            savedAttrs.set(img, s);
            return applyToImage(img);
        }

        var mo = new MutationObserver(function (mutations) {
            var didRestore = false;
            mutations.forEach(function (m) {
                if (m.type === 'attributes' && m.target.tagName === 'IMG') {
                    if (applyToImage(m.target)) didRestore = true;
                } else if (m.type === 'childList') {
                    m.addedNodes.forEach(function (n) {
                        if (n.tagName === 'IMG') {
                            if (recoverBySrc(n)) didRestore = true;
                        } else if (n.querySelectorAll) {
                            n.querySelectorAll('img').forEach(function (img) {
                                if (recoverBySrc(img)) didRestore = true;
                            });
                        }
                    });
                }
            });
            if (didRestore) syncTextarea();
        });
        mo.observe(editorBody, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'width', 'height', 'data-align'],
        });

        // Whenever we snapshot an image, also remember by src.
        var origSnapshot = snapshot;
        snapshot = function (img) {
            origSnapshot(img);
            rememberBySrc(img);
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
