

const addFormToCollection = (e) => {

    const collectionHolder = document.querySelector('[data-identifier="' + e.currentTarget.dataset.collectionHolderClass + '"]');
    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    // add a delete link to the new form
    addMarchandiseFormDeleteLink(item);
};

document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

document
    .querySelectorAll('ul.tags li')
    .forEach((marchandise) => {
        addMarchandiseFormDeleteLink(marchandise)
    })

const addMarchandiseFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer ce colis';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}
const myField = document.getElementById('password');
myField.addEventListener('mouseleave', function() {
  alert('Vous avez quitté le champ de formulaire.');
});
