function addElement(parentId, elementTag, elementId, elementClass, html) {
  var p = document.getElementById(parentId);
  var newElement = document.createElement(elementTag);
  newElement.setAttribute('id', elementId);
  if (elementClass) {
    newElement.setAttribute('class', elementClass);
  }
  newElement.innerHTML = html;
  p.appendChild(newElement);
}

function removeElement(elementId) {
  var element = document.getElementById(elementId);
  element.parentNode.removeChild(element);
}

var ingredId = 0;
function addIngredient() {
  ingredId++;
  var html = `
    <input class="amt" name="amt` + ingredId + `" type="text" placeholder="Amount"/>
    <input class="unit" name="unit` + ingredId + `" type="text" placeholder="Unit"/>
    <input class="name" name="name` + ingredId + `" type="text" placeholder="Ingredient"/>
    <button type="button" onclick="removeElement('ingred` + ingredId  + `');">-</button>
  `;
  addElement('ingred-inputs', 'div', 'ingred' + ingredId, '', html);
}

var tagId = 0;
function addTag() {
  if (getTagAmt() >= 5) {
    return;
  }
  tagId++;
  var html = `
    <input class="tag" name="tag` + tagId + `" type="text" placeholder="Tag"/>
    <button type="button" onclick="removeElement('tag` + tagId  + `');">-</button>
  `;
  addElement('tag-inputs', 'div', 'tag' + tagId, '', html);
}

function getTagAmt() {
  var list = document.querySelectorAll('.tag');
  return list.length;
}

var prepId = 0;
function addDesc() {
  prepId++;
  var html = `
    <textarea class="prepdesc" name="desc` + prepId + `" placeholder="Description"></textarea>
    <button type="button" onclick="removeElement('prep` + prepId  + `');">-</button>
  `
  addElement('prep-inputs', 'div', 'prep' + prepId, 'prepstep', html);
}

function handleFileSelect(evt) {
  var files = evt.target.files;
  var f = files[0];
  var reader = new FileReader();

  reader.onload = (function(theFile) {
    return function(e) {
      document.querySelector("#img-preview").innerHTML = ['<img src="', e.target.result,'" title="', theFile.name, '"/>'].join('');
    };
  })(f);

  reader.readAsDataURL(f);
}
