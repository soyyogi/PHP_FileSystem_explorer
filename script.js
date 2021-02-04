// show new item options
function newItem(e) {
    e.stopPropagation();
    document.querySelector('.item-options').classList.toggle('hidden');
}
document.querySelector('.create-new-item').addEventListener('click', newItem);


// show create item form
function createItem() {
    document.querySelector('.create-item-form').classList.remove('hidden');
    const type = this.classList[1];
    document.querySelector('.create-item-form #type').value = type;
    if(type !== 'dir') {
        document.querySelector('.create-item-form #body').removeAttribute('hidden');
    }
}
document.querySelectorAll('.item-option').forEach(item => item.addEventListener('click', createItem));


// hide new item dropdown and form on outside click
document.querySelector('.create-item-form').addEventListener('click', e => e.stopPropagation());
function hideElement(e) {
    if(!document.querySelector('.create-item-form').classList.contains('hidden')) {
        document.querySelector('.create-item-form').classList.add('hidden');
        document.querySelector('.create-item-form #body').value = '';
        document.querySelector('.create-item-form #title').value = '';
        document.querySelector('.create-item-form #body').setAttribute('hidden', true);
    }
    if(!document.querySelector('.item-options').classList.contains('hidden')) {
        document.querySelector('.item-options').classList.toggle('hidden');
    }
}
document.body.addEventListener('click', hideElement);