/**
 * Admin Rich Text Editor (Quill 2) — with Word-style image tools
 * --------------------------------------------------------------
 * Auto-converts every <textarea data-rich-editor> into a WYSIWYG editor.
 * The hidden textarea stays in the form so the existing PHP handler keeps
 * working — we just sync the HTML back on every change.
 *
 * IMAGE RESIZE/ALIGN — three independent code paths
 * --------------------------------------------------
 * 1. CLICK + OVERLAY (primary UX, like MS Word)
 *    Click an image -> floating toolbar with L/C/R + 25/50/100% +
 *    delete buttons appears, plus a corner drag-handle.
 *
 * 2. KEYBOARD SHORTCUTS (fallback)
 *    With an image selected (clicked once):
 *      [   shrinks the image by 10% of editor width
 *      ]   grows it by 10%
 *      Backspace/Delete  removes it
 *
 * 3. RESIZE PROMPT (always-available fallback)
 *    Double-click an image -> prompt() asking for a percentage,
 *    no UI dependencies at all. Guaranteed to work even if the
 *    overlay code has any bug whatsoever.
 *
 * Storage strategy
 * ----------------
 * Width is set via `img.setAttribute('width', '<pct>%')` — Quill 2's
 * default Image blot whitelists `width`, so it survives normalisation
 * and round-trips through .innerHTML. Alignment uses a `data-align`
 * attribute paired with CSS rules in admin-editor.css and (for the
 * public side) style.css.
 *
 * A MutationObserver watches the editor and re-applies cached
 * width/align if Quill ever wipes them (e.g. on undo/redo).
 */
(function () {
    if (typeof Quill === 'undefined') {
        console.warn('[admin-editor] Quill not loaded');
        return;
    }

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

        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            textarea.value = html === '<p><br></p>' ? '' : html;
        });

        attachImageTools(quill, textarea);

        // Floating help tip — appears once below the editor explaining
        // the image resize/align affordance, dismissible.
        attachImageHelpTip(wrap);

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

    /* ============================================================
     * IMAGE TOOLS — overlay approach
     * ------------------------------------------------------------
     * The overlay sits OVER the editor area absolutely, contains the
     * resize handle and floating toolbar. It tracks the currently
     * selected image. We never insert anything into the Quill editor
     * itself — only update attributes on the <img> via setAttribute,
     * which Quill 2 preserves natively for `width`.
     *
     * For alignment we set a `data-align` attribute and a matching
     * CSS class on the image via stylesheet (.ql-editor img[data-align="left"]
     * { float: left; ... }). The data-align attribute is something we
     * re-apply via a small MutationObserver if Quill ever drops it.
     * ============================================================ */
    function attachImageTools(quill, textarea) {
        var editorBody = quill.root;          // .ql-editor element
        var editorContainer = editorBody.parentNode; // .ql-container
        var current = null;
        var overlay = null;
        var dragging = false;
        var startX = 0, startWPx = 0;

        // src -> { width, align } cache so we can restore after Quill
        // rebuilds the DOM (paste, undo, format on neighbour, etc.)
        var srcCache = {};

        function ensureContainerPositioned() {
            // Make the container a positioning context so the overlay
            // stays anchored to images even when the page scrolls or
            // the editor is in a flex/grid layout.
            var pos = window.getComputedStyle(editorContainer).position;
            if (pos === 'static') editorContainer.style.position = 'relative';
        }

        function buildOverlay() {
            if (overlay) return overlay;
            ensureContainerPositioned();
            overlay = document.createElement('div');
            overlay.className = 'rich-image-overlay';
            overlay.style.display = 'none';
            overlay.innerHTML = ''
                + '<div class="rich-image-toolbar" data-rio-toolbar>'
                +   '<button type="button" data-action="align-left"   title="Rata Kiri"><i class="fas fa-align-left"></i></button>'
                +   '<button type="button" data-action="align-center" title="Rata Tengah"><i class="fas fa-align-center"></i></button>'
                +   '<button type="button" data-action="align-right"  title="Rata Kanan"><i class="fas fa-align-right"></i></button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="size-25"  title="25%">25%</button>'
                +   '<button type="button" data-action="size-50"  title="50%">50%</button>'
                +   '<button type="button" data-action="size-100" title="100%">100%</button>'
                +   '<span class="rich-image-sep"></span>'
                +   '<button type="button" data-action="remove" title="Hapus"><i class="fas fa-trash"></i></button>'
                + '</div>'
                + '<div class="rich-image-handle" data-rio-handle title="Seret untuk ubah ukuran"></div>';
            editorContainer.appendChild(overlay);

            // Toolbar clicks
            overlay.querySelector('[data-rio-toolbar]').addEventListener('mousedown', function (e) {
                e.preventDefault(); // keep focus on the image
            });
            overlay.querySelector('[data-rio-toolbar]').addEventListener('click', function (e) {
                var btn = e.target.closest('button[data-action]');
                if (!btn || !current) return;
                e.preventDefault();
                e.stopPropagation();
                applyAction(btn.getAttribute('data-action'));
            });

            // Resize handle
            var handle = overlay.querySelector('[data-rio-handle]');
            handle.addEventListener('mousedown', startResize);
            handle.addEventListener('touchstart', startResize, { passive: false });

            return overlay;
        }

        function setWidthPct(img, pct) {
            // The HTML width attribute is what Quill 2's default Image
            // blot whitelists, so this survives normalisation.
            img.setAttribute('width', pct + '%');
            // Inline style is a defence-in-depth + makes height stay
            // proportional during the drag (browsers infer height from
            // intrinsic ratio when only width % is set).
            img.style.width  = pct + '%';
            img.style.height = 'auto';
            cacheBySrc(img);
        }

        function setAlign(img, side) {
            // side: 'left' | 'center' | 'right' | null
            if (!side) {
                img.removeAttribute('data-align');
            } else {
                img.setAttribute('data-align', side);
            }
            cacheBySrc(img);
        }

        function cacheBySrc(img) {
            if (!img.src) return;
            srcCache[img.src] = {
                width: img.getAttribute('width'),
                align: img.getAttribute('data-align'),
            };
        }

        function recoverFromCache(img) {
            if (!img || !img.src) return false;
            var s = srcCache[img.src];
            if (!s) return false;
            var changed = false;
            if (s.width && img.getAttribute('width') !== s.width) {
                img.setAttribute('width', s.width);
                img.style.width  = s.width;
                img.style.height = 'auto';
                changed = true;
            }
            if (s.align && img.getAttribute('data-align') !== s.align) {
                img.setAttribute('data-align', s.align);
                changed = true;
            }
            return changed;
        }

        function applyAction(action) {
            if (!current) return;
            switch (action) {
                case 'align-left':   setAlign(current, 'left');   break;
                case 'align-center': setAlign(current, 'center'); break;
                case 'align-right':  setAlign(current, 'right');  break;
                case 'size-25':  setWidthPct(current, 25);  break;
                case 'size-50':  setWidthPct(current, 50);  break;
                case 'size-100': setWidthPct(current, 100); break;
                case 'remove':
                    current.parentNode && current.parentNode.removeChild(current);
                    hide();
                    syncTextarea();
                    return;
            }
            position();
            syncTextarea();
        }

        function syncTextarea() {
            if (!textarea) return;
            // Read directly from the editor's DOM. Quill 2 preserves
            // the width attribute, and our MutationObserver below
            // restores anything else that got stripped.
            var html = quill.root.innerHTML;
            textarea.value = html === '<p><br></p>' ? '' : html;
        }

        function position() {
            if (!current || !overlay) return;
            // Position relative to the editor CONTAINER, not the page.
            // This is robust against page scroll, sticky headers,
            // transformed parents, etc.
            var imgRect = current.getBoundingClientRect();
            var ctRect  = editorContainer.getBoundingClientRect();
            overlay.style.top    = (imgRect.top - ctRect.top) + 'px';
            overlay.style.left   = (imgRect.left - ctRect.left) + 'px';
            overlay.style.width  = imgRect.width  + 'px';
            overlay.style.height = imgRect.height + 'px';
            overlay.style.display = 'block';
        }

        function show(img) {
            current = img;
            buildOverlay();
            position();
        }
        function hide() {
            current = null;
            if (overlay) overlay.style.display = 'none';
        }

        function startResize(e) {
            if (!current) return;
            e.preventDefault();
            e.stopPropagation();
            dragging = true;
            var p = e.touches ? e.touches[0] : e;
            startX = p.clientX;
            startWPx = current.getBoundingClientRect().width;

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
            var newW = Math.max(40, startWPx + dx);

            var bodyW = editorBody.clientWidth - 8;
            if (bodyW < 40) bodyW = 40;
            if (newW > bodyW) newW = bodyW;

            var pct = Math.round((newW / bodyW) * 100);
            pct = Math.max(10, Math.min(100, pct));
            setWidthPct(current, pct);
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

        // Click on an image -> select it.
        editorBody.addEventListener('click', function (e) {
            var img = e.target.closest('img');
            if (img && editorBody.contains(img)) {
                show(img);
                e.stopPropagation();
            } else {
                hide();
            }
        });

        // Double-click on an image -> prompt for a custom width percent.
        // Fallback path that doesn't depend on the overlay UI at all —
        // works even if CSS breaks or the overlay misposiciones.
        editorBody.addEventListener('dblclick', function (e) {
            var img = e.target.closest('img');
            if (!img || !editorBody.contains(img)) return;
            e.preventDefault();
            e.stopPropagation();
            var currentWidth = img.getAttribute('width') || '100%';
            var input = window.prompt(
                'Ukuran gambar dalam persen (10–100):\n' +
                '— Kosongkan untuk 100% (full width)\n' +
                '— Contoh: 50 untuk setengah lebar editor',
                String(currentWidth).replace('%', '')
            );
            if (input === null) return; // cancelled
            var pct = parseInt(input, 10);
            if (isNaN(pct) || pct <= 0) pct = 100;
            pct = Math.max(10, Math.min(100, pct));
            setWidthPct(img, pct);
            show(img); // re-position overlay
            syncTextarea();
        });

        // Keyboard shortcuts when an image is selected:
        //   [ shrinks 10%, ] grows 10%, Delete/Backspace removes
        document.addEventListener('keydown', function (e) {
            if (!current) return;
            // Don't intercept when typing in an input/textarea elsewhere
            var t = e.target;
            if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA')) return;
            if (e.key === '[' || e.key === ']') {
                e.preventDefault();
                var w = current.getAttribute('width') || '100%';
                var p = parseInt(String(w).replace('%', ''), 10);
                if (isNaN(p)) p = 100;
                p = e.key === '[' ? Math.max(10, p - 10) : Math.min(100, p + 10);
                setWidthPct(current, p);
                position();
                syncTextarea();
            } else if (e.key === 'Delete' || e.key === 'Backspace') {
                if (current.parentNode) {
                    e.preventDefault();
                    current.parentNode.removeChild(current);
                    hide();
                    syncTextarea();
                }
            }
        });

        // Click outside both editor and overlay -> deselect.
        document.addEventListener('mousedown', function (e) {
            if (!current) return;
            if (overlay && overlay.contains(e.target)) return;
            if (editorBody.contains(e.target)) return;
            hide();
        });

        window.addEventListener('scroll', function () { if (current) position(); }, true);
        window.addEventListener('resize', function () { if (current) position(); });

        // MutationObserver: re-apply cached width/align if Quill ever
        // wipes them when rebuilding the DOM.
        var mo = new MutationObserver(function (mutations) {
            var didRestore = false;
            mutations.forEach(function (m) {
                if (m.type === 'attributes' && m.target.tagName === 'IMG') {
                    if (recoverFromCache(m.target)) didRestore = true;
                } else if (m.type === 'childList') {
                    m.addedNodes.forEach(function (n) {
                        if (n.nodeType !== 1) return;
                        if (n.tagName === 'IMG') {
                            if (recoverFromCache(n)) didRestore = true;
                        } else if (n.querySelectorAll) {
                            n.querySelectorAll('img').forEach(function (img) {
                                if (recoverFromCache(img)) didRestore = true;
                            });
                        }
                    });
                }
            });
            if (didRestore) {
                if (current) position();
                syncTextarea();
            }
        });
        mo.observe(editorBody, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['width', 'data-align', 'src'],
        });

        // Cache any images already in the editor on load.
        editorBody.querySelectorAll('img').forEach(cacheBySrc);
    }

    function init() {
        document.querySelectorAll('textarea[data-rich-editor]').forEach(buildEditor);
    }

    /* ============================================================
     * HELP TIP — small banner under each editor explaining the
     * image resize gesture so users discover it.
     * ============================================================ */
    function attachImageHelpTip(wrap) {
        if (wrap.dataset.tipAttached === '1') return;
        wrap.dataset.tipAttached = '1';
        var dismissed = false;
        try { dismissed = localStorage.getItem('rich-editor-tip-dismissed') === '1'; } catch (e) {}
        if (dismissed) return;

        var tip = document.createElement('div');
        tip.className = 'rich-editor-tip';
        tip.innerHTML =
            '<i class="fas fa-info-circle"></i>' +
            '<span><strong>Tips gambar:</strong> klik foto → muncul toolbar untuk resize 25/50/100% & alignment kiri/tengah/kanan. ' +
            'Atau <strong>double-klik</strong> foto untuk masukkan persen manual. Tombol <kbd>[</kbd> / <kbd>]</kbd> kecilkan/besarkan 10%.</span>' +
            '<button type="button" class="rich-editor-tip-close" title="Sembunyikan">&times;</button>';
        wrap.parentNode.insertBefore(tip, wrap.nextSibling);
        tip.querySelector('.rich-editor-tip-close').addEventListener('click', function () {
            tip.remove();
            try { localStorage.setItem('rich-editor-tip-dismissed', '1'); } catch (e) {}
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
