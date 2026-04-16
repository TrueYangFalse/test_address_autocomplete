const input = document.getElementById('address-input');
const button = document.getElementById('select-button');
const suggestions = document.getElementById('suggestions');
const selectedAddress = document.getElementById('selected-address');

let debounceTimer = null;
let selectedFromList = null;
let latestItems = [];

function clearSuggestions() {
    suggestions.innerHTML = '';
}

function showInfo(message, isError = false) {
    selectedAddress.textContent = message;
    selectedAddress.classList.toggle('selected-address-error', isError);
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
            showInfo('Нажмите "Выбрать", чтобы подтвердить адрес');
            clearSuggestions();
        });

        suggestions.appendChild(li);
    });
}

async function fetchSuggestions(query) {
    if (query.trim().length < 2) {
        latestItems = [];
        clearSuggestions();
        return;
    }

    const response = await fetch(`/api/addresses.php?q=${encodeURIComponent(query)}`);
    const data = await response.json();
    latestItems = data.items || [];
    renderSuggestions(latestItems);
}

async function validateAddress(typedValue) {
    if (latestItems.some((item) => item.full_address === typedValue)) {
        return true;
    }

    const response = await fetch(`/api/addresses.php?q=${encodeURIComponent(typedValue)}`);
    const data = await response.json();
    const items = data.items || [];

    return items.some((item) => item.full_address === typedValue);
}

input.addEventListener('input', () => {
    selectedFromList = null;

    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    debounceTimer = setTimeout(() => {
        fetchSuggestions(input.value).catch(() => clearSuggestions());
    }, 250);
});

button.addEventListener('click', async () => {
    const typedValue = input.value.trim();

    if (!typedValue) {
        showInfo('Введите адрес перед выбором', true);
        clearSuggestions();
        return;
    }

    const isValid = selectedFromList === typedValue || await validateAddress(typedValue);
    const value = isValid ? typedValue : null;

    if (!value) {
        showInfo('Данного адреса не существует. Попробуйте другой адрес следуя шаблону: "г Москва, ул Тверская, д 1"', true);
        return;
    }

    showInfo(value, false);
    clearSuggestions();
});
