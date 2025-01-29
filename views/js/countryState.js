// const country = document.getElementById("selectCountry");
// const state = document.getElementById("selectStates");
// state.disabled = true;
// country.addEventListener("change", stateHandle);

// function stateHandle() {
//     if (country.value === " ") {
//         state.disabled = true;
//     } else {
//         state.disabled = false;
//     }
// }

// // dynamic
// document.addEventListener('DOMContentLoaded', function () {
//     console.log("on DOm");

//     const countries = {
//         "India": ["Gujarat", "Maharastra", "Tamilnadu", "Rajasthan"],
//         "canada": ["Alberta", "BritishColumbia", "Manitoba", "Quebec"],
//         "USA": ["California", "Alaska", "Georgia"],
//         "Japan": ["Hokkaido", "Fukushima", "Hiroshima"]
//     };
//     const countrySelect = document.getElementById('selectCountry');
//     const stateSelect = document.getElementById('selectStates');
//     const selectedCountry = "<?php echo isset($_POST['country']) ? $_POST['country'] : ''; ?>";
//     const selectedState = "<?php  echo isset($_POST['states']) ? $_POST['states'] : ''; ?>";

//     // console.log("selectedCountry", selectedCountry);

//     for (let country in countries) {
//         // console.log("country", country);

//         let option = document.createElement('option');
//         option.value = country;
//         option.textContent = country;
//         if (selectedCountry && country == selectedCountry) {
//             option.selected = true;
//         }
//         // console.log("option", option);

//         countrySelect.appendChild(option);
//     }

//     stateSelect.innerHTML = '<option value="" disabled selected>Select a state</option>';

//     let states = countries[countrySelect.value];
//     if (states) {
//         console.log("dada");

//         for (let state of states) {
//             let option = document.createElement('option');
//             option.value = state;
//             option.innerText = state;
//             if (selectedState && state == selectedState) {
//                 option.selected = true;
//             }
//             console.log("option", option);

//             stateSelect.appendChild(option);
//         }
//     }

//     countrySelect.addEventListener('change', function () {
//         console.log("nonad add");

//         stateSelect.innerHTML = '<option value="" disabled selected>Select a state</option>';

//         let states = countries[countrySelect.value];
//         for (let state of states) {
//             let option = document.createElement('option');
//             option.value = state;
//             option.innerText = state;
//             if (selectedState && state == selectedState) {
//                 option.selected = true;
//             }
//             console.log("option", option);

//             stateSelect.appendChild(option);
//         }
//     });
// })
