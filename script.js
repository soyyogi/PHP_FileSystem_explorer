function newItem() {
    document.querySelector('.item-options').classList.toggle('hidden');
}
document.querySelector('.create-new-item').addEventListener('click', newItem);



function createItem() {
    document.querySelector('#create-item-form').style.display = 'flex';
    const type = this.classList[1];
    document.querySelector('#create-item-form #type').value = type;
    if(type !== 'dir') {
        document.querySelector('#create-item-form #body').removeAttribute('hidden');
    }
}
document.querySelectorAll('.item-option').forEach(item => item.addEventListener('click', createItem));