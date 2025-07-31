// Line-numbered textarea logic for Laravel Livewire integration
(function() {
    window.initLineNumberedTextareas = function() {
        var containers = document.querySelectorAll('.linenumbered-textarea');
        console.log(containers)
        containers.forEach(function(container) {
            // Prevent double-initialization
            console.log('found me');
            if (container.dataset.linenumbersInitialized) return;
            container.dataset.linenumbersInitialized = "true";
            var linenumbers = container.querySelector('.line-numbers');
            var editor = container.querySelector('textarea');
            var hiddenInput = container.querySelector('input[type="hidden"]');

            function getWidth(elem) {
                return elem.scrollWidth - (parseFloat(window.getComputedStyle(elem, null).getPropertyValue('padding-left')) + parseFloat(window.getComputedStyle(elem, null).getPropertyValue('padding-right')));
            }
            function getFontSize(elem) {
                return parseFloat(window.getComputedStyle(elem, null).getPropertyValue('font-size'));
            }
            function cutLines(lines) {
                return lines.split(/\r?\n/);
            }
            function getLineHeight(elem) {
                var computedStyle = window.getComputedStyle(elem);
                var lineHeight = computedStyle.getPropertyValue('line-height');
                var lineheight;
                if (lineHeight === 'normal') {
                    var fontSize = computedStyle.getPropertyValue('font-size');
                    lineheight = parseFloat(fontSize) * 1.2;
                } else {
                    lineheight = parseFloat(lineHeight);
                }
                return lineheight;
            }
            function getTotalLineSize(size, line, options) {
                if (typeof options === 'object') options = {};
                var p = document.createElement('span');
                p.style.setProperty('white-space', 'pre');
                p.style.display = 'inline-block';
                if (typeof options.fontSize !== 'undefined') p.style.fontSize = options.fontSize;
                p.innerHTML = line;
                document.body.appendChild(p);
                var result = (p.scrollWidth / size);
                p.remove();
                return Math.ceil(result);
            }
            function getLineNumber() {
                var textLines = editor.value.substr(0, editor.selectionStart).split("\n");
                var currentLineNumber = textLines.length;
                return currentLineNumber;
            }
            function updateLineNumbers() {
                var totallines = cutLines(editor.value), linesize;
                linenumbers.innerHTML = '';
                for (var i = 1; i <= totallines.length; i++) {
                    var num = document.createElement('p');
                    num.innerHTML = i;
                    linenumbers.appendChild(num);
                    linesize = getTotalLineSize(getWidth(editor), totallines[(i - 1)], {'fontSize' : getFontSize(editor)});
                    if (linesize > 1) {
                        num.style.height = (linesize * getLineHeight(editor)) + 'px';
                    }
                }
                linesize = getTotalLineSize(getWidth(editor), totallines[(getLineNumber() - 1)], {'fontSize' : getFontSize(editor)});
                if (linesize > 1 && linenumbers.childNodes.length > 0) {
                    linenumbers.childNodes[(getLineNumber() - 1)].style.height = (linesize * getLineHeight(editor)) + 'px';
                }
                editor.style.height = editor.scrollHeight + 'px';
                linenumbers.style.height = editor.scrollHeight + 'px';
                // Sync to Livewire
                if (hiddenInput) {
                    hiddenInput.value = editor.value;
                    hiddenInput.dispatchEvent(new Event('input'));
                }
            }
            editor.addEventListener('keyup', updateLineNumbers);
            editor.addEventListener('input', updateLineNumbers);
            editor.addEventListener('click', updateLineNumbers);
            editor.addEventListener('paste', updateLineNumbers);
            editor.addEventListener('mouseover', updateLineNumbers);
            editor.addEventListener('focus', updateLineNumbers);
            editor.addEventListener('change', updateLineNumbers);
            // Initialize with value from hidden input if present
            if (hiddenInput && hiddenInput.value) {
                editor.value = hiddenInput.value;
            }
            updateLineNumbers();
        });
    };
    // Run on DOMContentLoaded
    // document.addEventListener('livewire:navigated', window.initLineNumberedTextareas);
})(); 