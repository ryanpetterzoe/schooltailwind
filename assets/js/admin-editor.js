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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
