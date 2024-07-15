
export const initGrams2OuncesShortcode = () => {
    console.log("grams to oz")
    let grams2OuncesForm = document.querySelectorAll('.grams2ounces__form');

    grams2OuncesForm.forEach(form => {
        form.addEventListener('input',  (e) => {
            e.preventDefault();

            convertGramsToOunces(e.target);
        })
    });
}

export const convertGramsToOunces = (target) => {
    let grams = 'grams2ounces-grams';
    let ounces = 'grams2ounces-ounces';

    if(grams === target.id) {
        document.getElementById(ounces).value = (target.value * 0.03527396194958).toFixed(6);
    } else if(ounces === target.id) {
        document.getElementById(grams).value = (target.value / 0.03527396194958).toFixed(2);
    }
}

