// Use IIFE to avoid redeclaration errors when script loads multiple times
(function() {
    'use strict';

    // Initialize phoneCodesData on window if it doesn't exist
    if (typeof window.phoneCodesData === 'undefined') {
        window.phoneCodesData = [];
    }

    async function loadPhoneCodes(apiUrl = '/dashboard/phone-codes') {
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.success && data.data) {
                window.phoneCodesData = data.data;
                return data.data;
            }
            return [];
        } catch (error) {
            console.error('Error loading phone codes:', error);
            return [];
        }
    }

    async function populatePhoneCodeSelect(selectId, defaultCode = '+966', apiUrl = '/dashboard/phone-codes') {
        const selectElement = document.getElementById(selectId);
        if (!selectElement) {
            console.error(`Element with ID '${selectId}' not found`);
            return;
        }

        try {
            const data = await loadPhoneCodes(apiUrl);

            if (data.length > 0) {
                selectElement.innerHTML = '';

                const existingHiddenInput = selectElement.parentNode.querySelector('input[name="phone_code"][type="hidden"]');
                if (existingHiddenInput) {
                    existingHiddenInput.remove();
                }

                if (data.length === 1) {
                    selectElement.style.display = 'none';
                    selectElement.removeAttribute('name');
                    selectElement.value = data[0].code;

                    const span = document.createElement('span');
                    span.className = 'input-group-text input-code';
                    span.textContent = `${data[0].flag} ${data[0].code}`;
                    selectElement.parentNode.insertBefore(span, selectElement);

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'phone_code';
                    hiddenInput.value = data[0].code;
                    selectElement.parentNode.appendChild(hiddenInput);
                } else {
                    selectElement.style.display = '';
                    selectElement.setAttribute('name', 'phone_code');

                    data.forEach(function(phoneCode, index) {
                        const option = document.createElement('option');
                        option.value = phoneCode.code;
                        option.textContent = `${phoneCode.flag} ${phoneCode.code}`;

                        if (index === 0 || phoneCode.code === defaultCode) {
                            option.selected = true;
                        }

                        selectElement.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error('Error populating phone code select:', error);
            selectElement.innerHTML = '<option value="+966">🇸🇦 +966</option><option value="+20">🇪🇬 +20</option>';
        }
    }

    function getPhoneCodeHelpText(phoneCode) {
        if (!phoneCode) {
            return 'اختر كود الدولة أولاً.';
        }

        const phoneCodeData = window.phoneCodesData.find(code => code.code === phoneCode);
        if (phoneCodeData && phoneCodeData.help_text) {
            return phoneCodeData.help_text;
        }

        return `كود الدولة: ${phoneCode}`;
    }

    function updatePhoneCodeHelpText(selectId, helpTextId) {
        const selectElement = document.getElementById(selectId);
        const helpTextElement = document.getElementById(helpTextId);

        if (!selectElement || !helpTextElement) {
            console.error('Select or help text element not found');
            return;
        }

        const selectedCode = selectElement.value;
        helpTextElement.textContent = getPhoneCodeHelpText(selectedCode);

        if (selectElement.style.display === 'none' && window.phoneCodesData.length === 1) {
            helpTextElement.textContent = getPhoneCodeHelpText(window.phoneCodesData[0].code);
        }
    }

    async function initPhoneCodeForm(options) {
        const {
            selectId,
            helpTextId,
            defaultCode = '+966',
            apiUrl = '/dashboard/phone-codes'
        } = options;

        await populatePhoneCodeSelect(selectId, defaultCode, apiUrl);

        const selectElement = document.getElementById(selectId);
        if (selectElement && helpTextId) {
            if (selectElement.style.display !== 'none') {
                selectElement.addEventListener('change', function() {
                    updatePhoneCodeHelpText(selectId, helpTextId);
                });
            }

            updatePhoneCodeHelpText(selectId, helpTextId);
        }
    }

    function getAllPhoneCodes() {
        return window.phoneCodesData;
    }

    function findPhoneCodeData(phoneCode) {
        return window.phoneCodesData.find(code => code.code === phoneCode) || null;
    }

    // Expose functions on window only if not already defined (to avoid overwriting)
    if (typeof window.PhoneCodes === 'undefined') {
        window.PhoneCodes = {
            loadPhoneCodes,
            populatePhoneCodeSelect,
            getPhoneCodeHelpText,
            updatePhoneCodeHelpText,
            initPhoneCodeForm,
            getAllPhoneCodes,
            findPhoneCodeData
        };
    }
})();
