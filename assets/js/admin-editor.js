/**
 * Admin Rich Text Editor (Quill 2)
 * --------------------------------
 * Auto-converts every <textarea data-rich-editor> into a WYSIWYG editor
 * with a Word-like toolbar (heading, bold/italic, color, lists, alignment,
 * link, image). The original textarea stays in the DOM (hidden) so the
 * existing PHP form handling keeps working — we just sync the HTML back
 * before submit. No API key, no external service.
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

    /* ------------------------------------------------------------
     * Custom Image blot that preserves inline style + width/height +
     * align attribute. Out of the box Quill 2 only keeps `src` and
     * `alt`, so the `style="width: 50%; float: left"` we set when the
     * user resizes/aligns gets stripped on the next text-change.
     *
     * We register a sub-class of the built-in Image format that adds
     * `style`, `width`, `height`, `align` to its allowed attributes.
     * ------------------------------------------------------------ */
    try {
        var ImageBlot = Quill.import('formats/image');
        if (ImageBlot && !ImageBlot.__resizableRegistered) {
            // Tell Quill which DOM attributes to keep when serializing the blot
            ImageBlot.sanitize = function (url) { return url; };
            // Whitelist additional <img> attributes we want preserved
            var EXTRA_ATTRS = ['style', 'width', 'height', 'align', 'data-align'];
            EXTRA_ATTRS.forEach(function (attr) {
                // Quill walks ATTRIBUTES on Image when reading from DOM
                if (ImageBlot.ATTRIBUTES && ImageBlot.ATTRIBUTES.indexOf(attr) === -1) {
                    ImageBlot.ATTRIBUTES.push(attr);
                }
            });
            // For Quill 2 the built-in Image uses formats(domNode) /
            // create(value). Override formats() so each image-blot
            // round-trips its style + align attributes.
            var origFormats = ImageBlot.formats;
            ImageBlot.formats = function (domNode) {
                var f = (typeof origFormats === 'function') ? origFormats.call(this, domNode) : {};
                EXTRA_ATTRS.forEach(function (attr) {
                    if (domNode.hasAttribute(attr)) {
                        f[attr] = domNode.getAttribute(attr);
                    }
                });
                return f;
            };
            // And on the prototype, so format(name, value) writes them back
            var protoFormat = ImageBlot.prototype.format;
            ImageBlot.prototype.format = function (name, value) {
                if (EXTRA_ATTRS.indexOf(name) !== -1) {
                    if (value) this.domNode.setAttribute(name, value);
                    else this.domNode.removeAttribute(name);
                } else if (typeof protoFormat === 'function') {
                    protoFormat.call(this, name, value);
                }
            };
            ImageBlot.__resizableRegistered = true;
            Quill.register(ImageBlot, true);
        }
    } catch (e) { /* Quill version without formats/image — silently ignore */ }

    // --- Toolbar presets ---
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

    // Lookup so the form-submit hook can find the Quill instance for a textarea
    var instances = new WeakMap();

    function buildEditor(textarea) {
        if (textarea.dataset.richInitialized === '1') return;

        var simple = textarea.hasAttribute('data-editor-simple');
        var height = parseInt(textarea.dataset.editorHeight, 10) || 320;
        var toolbar = simple ? SIMPLE_TOOLBAR : FULL_TOOLBAR;

        // Wrapper with Tailwind-friendly border so it blends with our forms
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

        // Hide textarea but keep it in the form so the name attribute submits.
        textarea.style.display = 'none';
        textarea.parentNode.insertBefore(wrap, textarea.nextSibling);

        var quill = new Quill(editorDiv, {
            theme: 'snow',
            placeholder: textarea.placeholder || 'Tulis konten di sini...',
            modules: {
                toolbar: toolbar,
            },
        });

        instances.set(textarea, quill);
        textarea.dataset.richInitialized = '1';

        // Sync HTML back to textarea on every change (so server-side validation
        // that reads the textarea value before submit still works).
        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            // Quill renders an empty editor as "<p><br></p>". For a required
            // field we want that to count as empty so HTML5 validation triggers.
            textarea.value = html === '<p><br></p>' ? '' : html;
        });

        // MS Word-like image resize + alignment.
        // Click an image -> show toolbar (L/C/R + preset sizes) and a
        // bottom-right corner drag handle. Inline style on the <img> is
        // what gets persisted, so the public side (which renders the HTML
        // through safeRichHtml) shows the same width/alignment without
        // needing any new server logic.
        attachImageTools(quill, editorDiv, textarea);

        // Belt-and-braces: re-sync right before the form is submitted
        var form = textarea.form;
        if (form && !form.dataset.richSubmitHook) {
            form.dataset.richSubmitHook = '1';
            form.addEventListener(
                'submit',
                function () {
                    form
                        .querySelectorAll('textarea[data-rich-editor]')
                        .forEach(function (ta) {
                            var q = instances.get(ta);
                            if (!q) return;
                            var h = q.root.innerHTML;
                            ta.value = h === '<p><br></p>' ? '' : h;
                        });
                },
                true
            );
        }
    }

    function init() {
        document
            .querySelectorAll('textarea[data-rich-editor]')
            .forEach(buildEditor);
    }

    /* ============================================================
       IMAGE TOOLS — Word-style resize + alignment
       ------------------------------------------------------------
       Native, no plugin required. We listen for clicks on <img>
       inside any Quill editor body, then overlay:
         - bottom-right drag handle (proportional resize)
         - floating toolbar with Left / Center / Right alignment
           and 25% / 50% / 100% preset width
       Width is persisted as inline `style="width: …%;"`. Alignment
       is persisted as `float: left` / `float: right` / `display:block;
       margin: 0 auto;`. Both survive `safeRichHtml()` server-side.
       ============================================================ */
    function attachImageTools(quill, editorDiv, textarea) {
        var current = null;     // currently selected <img>
        var overlay = null;     // wrapper that holds handle + toolbar
        var dragging = false;
        var startX = 0, startY = 0, startW = 0, startH = 0, ratio = 1;

        function ensureOverlay() {
            if (overlay) return overlay;
            overlay = document.createElement('div');
            overlay.className = 'rich-image-overlay';
            overlay.innerHTML = ''
                + '<div class="rich-image-toolbar">'
                +   '<button type="button" data-action="align-left"   title="Rata Kiri (teks mengalir di kanan)"><i class="fas fa-align-left"></i></button>'
                +   '<button type="button" data-action="align-center" title="Rata Tengah"><i class="fas fa-align-center"></i></button>'
                +   '<button type="button" data-action="align-right"  title="Rata Kanan (teks mengalir di kiri)"><i class="fas fa-align-right"></i></button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="size-25"  title="Lebar 25%">25%</button>'
                +   '<button type="button" data-action="size-50"  title="Lebar 50%">50%</button>'
                +   '<button type="button" data-action="size-100" title="Lebar 100%">100%</button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="remove" title="Hapus gambar"><i class="fas fa-trash"></i></button>'
                + '</div>'
                + '<div class="rich-image-handle" title="Seret untuk ubah ukuran"></div>';
            document.body.appendChild(overlay);

            // Toolbar actions
            overlay.querySelector('.rich-image-toolbar').addEventListener('mousedown', function (e) {
                // Prevent stealing focus from the image
                e.preventDefault();
            });
            overlay.querySelector('.rich-image-toolbar').addEventListener('click', function (e) {
                var btn = e.target.closest('button[data-action]');
                if (!btn || !current) return;
                e.preventDefault();
                applyAction(btn.getAttribute('data-action'));
            });

            // Resize handle
            var handle = overlay.querySelector('.rich-image-handle');
            handle.addEventListener('mousedown', startResize);
            handle.addEventListener('touchstart', startResize, { passive: false });
            return overlay;
        }

        function applyAction(action) {
            if (!current) return;
            switch (action) {
                case 'align-left':
                    current.style.float = 'left';
                    current.style.display = '';
                    current.style.margin = '0.4em 1em 0.4em 0';
                    break;
                case 'align-center':
                    current.style.float = 'none';
                    current.style.display = 'block';
                    current.style.margin = '0.6em auto';
                    break;
                case 'align-right':
                    current.style.float = 'right';
                    current.style.display = '';
                    current.style.margin = '0.4em 0 0.4em 1em';
                    break;
                case 'size-25':  current.style.width = '25%';  current.style.height = 'auto'; break;
                case 'size-50':  current.style.width = '50%';  current.style.height = 'auto'; break;
                case 'size-100': current.style.width = '100%'; current.style.height = 'auto'; break;
                case 'remove':
                    current.parentNode && current.parentNode.removeChild(current);
                    hide();
                    syncTextarea();
                    return;
            }
            // Cache the inline style we just set, so the MutationObserver
            // below can put it back if Quill's normalizer strips it.
            current.dataset.persistStyle = current.getAttribute('style') || '';
            position();
            syncTextarea();
        }

        function syncTextarea() {
            // Mirror the editor's HTML back into the hidden <textarea>
            // (which is what the form actually submits). Image attribute
            // changes don't fire a Quill text-change event so we have to
            // do this ourselves whenever the user resizes/aligns a photo.
            if (!textarea) return;
            var html = quill.root.innerHTML;
            textarea.value = html === '<p><br></p>' ? '' : html;
        }

        function position() {
            if (!current || !overlay) return;
            var r = current.getBoundingClientRect();
            // Account for page scroll (overlay is appended to <body>)
            var top  = r.top  + window.scrollY;
            var left = r.left + window.scrollX;
            overlay.style.top    = top + 'px';
            overlay.style.left   = left + 'px';
            overlay.style.width  = r.width + 'px';
            overlay.style.height = r.height + 'px';
            overlay.style.display = 'block';
        }

        function show(img) {
            current = img;
            ensureOverlay();
            position();
        }

        function hide() {
            current = null;
            if (overlay) overlay.style.display = 'none';
        }

        function startResize(e) {
            if (!current) return;
            e.preventDefault();
            dragging = true;
            var p = e.touches ? e.touches[0] : e;
            startX = p.clientX;
            startY = p.clientY;
            startW = current.getBoundingClientRect().width;
            startH = current.getBoundingClientRect().height;
            ratio  = startH > 0 ? (startW / startH) : 1;

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

            // Clamp to editor body width so the image never overflows
            var bodyW = editorDiv.clientWidth - 8;
            if (newW > bodyW) newW = bodyW;

            // Convert to % of the editor width so it stays responsive
            var pct = Math.round((newW / bodyW) * 100);
            pct = Math.max(10, Math.min(100, pct));
            current.style.width  = pct + '%';
            current.style.height = 'auto';
            current.dataset.persistStyle = current.getAttribute('style') || '';
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

        // Click on image in editor -> show overlay
        editorDiv.addEventListener('click', function (e) {
            var img = e.target.closest('img');
            if (img && editorDiv.contains(img)) {
                show(img);
                e.stopPropagation();
            } else {
                hide();
            }
        });

        // Belt-and-suspenders: if Quill ever strips the inline style
        // attribute on an <img> we set, restore it from data-persist-style.
        // This catches cases where the registered Image blot override
        // didn't take effect (e.g. older Quill version, OR an image was
        // pasted in and then resized before Quill rebuilt the model).
        var mo = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.type !== 'attributes') return;
                var img = m.target;
                if (!img || img.tagName !== 'IMG') return;
                var saved = img.dataset.persistStyle;
                if (!saved) return;
                if (m.attributeName === 'style' && img.getAttribute('style') !== saved) {
                    img.setAttribute('style', saved);
                    syncTextarea();
                }
            });
        });
        mo.observe(editorDiv, {
            attributes: true,
            subtree: true,
            attributeFilter: ['style', 'width', 'height'],
        });

        // Hide overlay when clicking outside or scrolling/resizing
        document.addEventListener('mousedown', function (e) {
            if (!current) return;
            if (overlay && overlay.contains(e.target)) return;
            if (e.target === current) return;
            hide();
        });
        window.addEventListener('scroll', function () { if (current) position(); }, true);
        window.addEventListener('resize', function () { if (current) position(); });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
