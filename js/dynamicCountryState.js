function countryStateDropdowns(countrySelectId , stateSelectId , selectedCountry ='' ,selectedState = '') {
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');

    fetch('../common/getCountryState.php?action=getCountries')
        .then(response => response.json())
        .then(countries => {
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.id;
                option.textContent = country.name;

                if (country.id === selectedCountry) {
                    option.selected = true;
                }
                countrySelect.appendChild(option);
            });
            if (selectedCountry) {
                fetchStates(selectedCountry, selectedState);
            }
        })
        .catch(error => console.error('Error fetching countries:', error));

    countrySelect.addEventListener('change', function () {
        const countryId = this.value;
        stateSelect.innerHTML = '<option value="">Select State</option>';

        if (countryId) {
            fetchStates(countryId);
        }
    });

    function fetchStates(countryId, preselectedState = '') {
        fetch(`../common/getCountryState.php?action=getStates&country_id=${countryId}`)
            .then(response => response.json())
            .then(states => {
                states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    if (state.id === preselectedState) {
                        option.selected = true;
                    }
                    stateSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching states:', error));
    }
}



