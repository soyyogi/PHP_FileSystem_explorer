function newItem() {
    document.querySelector('.item-options').classList.toggle('hidden');
}
document.querySelector('.create-new-item').addEventListener('click', newItem);



function createItem() {
    console.log(this)
}
document.querySelectorAll('.item-option').forEach(item => item.addEventListener('click', createItem));