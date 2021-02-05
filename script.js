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


// show upload file form
function uploadItem(e) {
    e.stopPropagation();
    document.querySelector('.create-item-form').classList.remove('hidden');
    document.querySelector('.create-item-form #type').value = 'upload';
    document.querySelector('.create-item-form').setAttribute('enctype', 'multipart/form-data');
    document.querySelector('.create-item-form #title').setAttribute('hidden', true);
    document.querySelector('.create-item-form #fileToUpload').removeAttribute('hidden');
}
document.querySelector('.upload-file').addEventListener('click', uploadItem);


// show action options
function showActions(e) {
    e.stopPropagation();
    this.querySelector('.action-options').classList.toggle('hidden');
}
document.querySelectorAll('.show-actions').forEach(item => item.addEventListener('click', showActions));



// hide new item dropdown and form on outside click
if(document.querySelector('.file-content') !== null){
    document.querySelector('.file-content').addEventListener('click', e => e.stopPropagation());
}
document.querySelector('.create-item-form').addEventListener('click', e => e.stopPropagation());
function hideElement(e) {
    if(!document.querySelector('.create-item-form').classList.contains('hidden')) {
        document.querySelector('.create-item-form').classList.add('hidden');
        document.querySelector('.create-item-form').removeAttribute('enctype');
        document.querySelector('.create-item-form #body').value = '';
        document.querySelector('.create-item-form #title').value = '';
        document.querySelector('.create-item-form #body').setAttribute('hidden', true);
        document.querySelector('.create-item-form #title').removeAttribute('hidden');
        document.querySelector('.create-item-form #fileToUpload').value = '';
        document.querySelector('.create-item-form #fileToUpload').setAttribute('hidden', true);
    }
    if(!document.querySelector('.item-options').classList.contains('hidden')) {
        document.querySelector('.item-options').classList.toggle('hidden');
    }
    document.querySelectorAll('.action-options').forEach(item => {
        if(!item.classList.contains('hidden')) {
            item.classList.toggle('hidden');
        }
    })
    if(document.querySelector('.file-content') !== null){
        if(!document.querySelector('.file-content').classList.contains('hidden')) {
            document.querySelector('.file-content').classList.add('hidden');
        }
    }
}
document.body.addEventListener('click', hideElement);



// search result
function checktype($name) {
    if ($name.endsWith('.txt')) {
        return 'txt';
    } else if ($name.endsWith('.txt')) {
        return 'docx';
    } else if ($name.endsWith('.jpeg') || $name.endsWith('.jpg') || $name.endsWith('.png')) {
        return 'img';
    } else if ($name.endsWith('.mp3')) {
        return 'mp3';
    } else if ($name.endsWith('.pdf')) {
        return 'pdf';
    } 
    return 'dir';
}
const displayList = document.querySelector('#displayList');
const search = document.querySelector('.input-search');
function findResult(e) {
    const filterResult = currentTree.filter(el => el.includes(e.target.value));
    displayList.innerHTML = '';
    filterResult.forEach(el => {
        displayList.innerHTML += `
        <li class="currentTree-item"><a href="${basePath}/actions.php?name=${el}&action=open">${icons[checktype(el)]} ${el}</a>
        <span class="show-actions">&#10247;
          <ul class="action-options hidden">
            <li class="action-option"><a href="${basePath}/actions.php?name=${el}&action=open">Open</a></li>
            <!-- <li class="action-option">Edit</li>
            <li class="action-option">Rename</li> -->
            <li class="action-option"><a href="${basePath}/actions.php?name=${el}&action=delete">Delete</a></li>
          </ul>
        </span>
        </li>
        `
    })
    document.querySelectorAll('.show-actions').forEach(item => item.addEventListener('click', showActions));
}
search.addEventListener('input', findResult);