const input = document.getElementById('address-input');
const button = document.getElementById('select-button');
const suggestions = document.getElementById('suggestions');
const selectedAddress = document.getElementById('selected-address');

let debounceTimer = null;
let selectedFromList = '';

function clearSuggestions() {
    suggestions.innerHTML = '';
}

function renderSuggestions(items) {
    clearSuggestions();

    items.forEach((item) => {
        const li = document.createElement('li');
        li.className = 'suggestion-item';
        li.textContent = item.full_address;

        li.addEventListener('click', () => {
            input.value = item.full_address;
            selectedFromList = item.full_address;
            clearSuggestions();
        });

        suggestions.appendChild(li);
    });
}

async function fetchSuggestions(query) {
    if (query.trim().length < 2) {
        clearSuggestions();
        return;
    }

    const response = await fetch(`/api/addresses.php?q=${encodeURIComponent(query)}`);
    const data = await response.json();
    renderSuggestions(data.items || []);
}

input.addEventListener('input', () => {
    selectedFromList = '';

    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    debounceTimer = setTimeout(() => {
        fetchSuggestions(input.value).catch(() => clearSuggestions());
    }, 250);
});

button.addEventListener('click', () => {
    const value = selectedFromList || input.value.trim();
    selectedAddress.textContent = value || 'Адрес не выбран';
    clearSuggestions();
});
