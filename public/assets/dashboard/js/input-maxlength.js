// Set maxlength for all input fields and prevent typing more than allowed
(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    const MAX_INPUT_LENGTH = 10000;

    function setupInputMaxLength(input) {
      // Set maxlength attribute
      if (!input.hasAttribute('maxlength') || parseInt(input.getAttribute('maxlength')) > MAX_INPUT_LENGTH) {
        input.setAttribute('maxlength', MAX_INPUT_LENGTH);
      }

      // Prevent typing more than max length
      input.addEventListener('input', function(e) {
        const currentLength = this.value.length;
        const maxLength = parseInt(this.getAttribute('maxlength')) || MAX_INPUT_LENGTH;

        if (currentLength > maxLength) {
          this.value = this.value.substring(0, maxLength);
        }
      });

      // Prevent paste that exceeds limit
      input.addEventListener('paste', function(e) {
        const maxLength = parseInt(this.getAttribute('maxlength')) || MAX_INPUT_LENGTH;
        const currentLength = this.value.length;
        const clipboardData = e.clipboardData || window.clipboardData;
        const pastedData = clipboardData.getData('text');

        if (currentLength + pastedData.length > maxLength) {
          e.preventDefault();
          const allowedLength = maxLength - currentLength;
          if (allowedLength > 0) {
            const trimmedData = pastedData.substring(0, allowedLength);
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + trimmedData + this.value.substring(end);
            this.setSelectionRange(start + trimmedData.length, start + trimmedData.length);
          }
        }
      });

      // Prevent keydown if at max length (except backspace, delete, arrow keys, etc.)
      input.addEventListener('keydown', function(e) {
        const maxLength = parseInt(this.getAttribute('maxlength')) || MAX_INPUT_LENGTH;
        const currentLength = this.value.length;

        // Allow special keys
        const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Home', 'End'];
        if (allowedKeys.includes(e.key) || e.ctrlKey || e.metaKey) {
          return true;
        }

        // If at max length and trying to type, prevent it
        // Allow if there's selected text (will be replaced)
        if (currentLength >= maxLength && this.selectionStart === this.selectionEnd) {
          e.preventDefault();
          return false;
        }
      });
    }

    // Set maxlength for existing inputs
    document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="url"], input[type="search"], textarea').forEach(setupInputMaxLength);

    // Watch for dynamically added inputs
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        mutation.addedNodes.forEach(function(node) {
          if (node.nodeType === 1) { // Element node
            // Check if the added node is an input
            if (node.tagName && ['INPUT', 'TEXTAREA'].includes(node.tagName)) {
              const inputTypes = ['text', 'email', 'tel', 'url', 'search'];
              if (node.tagName === 'INPUT' && inputTypes.includes(node.type)) {
                setupInputMaxLength(node);
              } else if (node.tagName === 'TEXTAREA') {
                setupInputMaxLength(node);
              }
            }
            // Check for inputs inside the added node
            if (node.querySelectorAll) {
              node.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="url"], input[type="search"], textarea').forEach(setupInputMaxLength);
            }
          }
        });
      });
    });

    // Start observing the document body for changes
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
})();
