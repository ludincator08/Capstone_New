let inclusion_s_form = document.getElementById('inclusion_s_form');

inclusion_s_form.addEventListener('submit', function(e){
  e.preventDefault();
  add_inclusion();
});

function add_inclusion(){
  let data = new FormData();
  data.append('name', inclusion_s_form.elements['inclusion_name'].value);
  data.append('icon', inclusion_s_form.elements['inclusion_icon'].files[0]); 
  data.append('desc', inclusion_s_form.elements['inclusion_desc'].value);
  data.append('add_inclusion', '');

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/inclusions_crud.php", true);

  xhr.onload  = function(){
      var myModal = document.getElementById('inclusion-s');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      if(this.responseText == 'inv_img'){
          alert('error', 'Only JPG and PNG images are allowed!');
      }
      else if(this.responseText == 'inv_size'){
          alert('error', 'Image should be less than 10MB!');
      }
      else if(this.responseText == 'upd_failed'){
          alert('error', 'Image upload failed. Server Down!');
      }
      else{
          alert('success', 'New inclusion added!');
          inclusion_s_form.reset();
          get_inclusions();
      }
  }
  xhr.send(data);
}

function get_inclusions(){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/inclusions_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
      document.getElementById('inclusions-data').innerHTML = this.responseText;
  }
  
  xhr.send('get_inclusions');
}

function upd_inclusion(id) {
  // Fetch inclusion data and populate the modal inputs
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/inclusions_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  
  xhr.onload = function () {
      let data = JSON.parse(this.responseText);

      document.getElementById('inclusion_name_inp').value = data.name;
      document.getElementById('inclusion_desc_inp').value = data.description;
      document.getElementById('current_icon_inp').value = data.icon; // Store the current icon path
      document.getElementById('inclusion_id_inp').value = id; // Store the inclusion ID
  }

  xhr.send('get_inclusion_id=' + id);
}


let edit_inclusion_s_form = document.getElementById('edit_inclusion_s_form');

edit_inclusion_s_form.addEventListener('submit', function (e) {
  e.preventDefault();
  edit_inclusion();
});

function edit_inclusion() {
  let data = new FormData();
  data.append('inclusion_id', edit_inclusion_s_form.elements['inclusion_id'].value);
  data.append('name', edit_inclusion_s_form.elements['inclusion_name_inp'].value);
  data.append('desc', edit_inclusion_s_form.elements['inclusion_desc_inp'].value);
  data.append('icon', edit_inclusion_s_form.elements['inclusion_icon_inp'].files[0]);
  data.append('edit_inclusion', '');

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/inclusions_crud.php", true);

  xhr.onload = function () {
      var myModal = document.getElementById('edit-inclusion-s');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      if (this.responseText == 'inv_img') {
          alert('error', 'Only JPG and PNG images are allowed!');
      }
      else if (this.responseText == 'inv_size') {
          alert('error', 'Image should be less than 10MB!');
      }
      else if (this.responseText == 'upd_failed') {
          alert('error', 'Image upload failed. Server Down!');
      }
      else {
          alert('success', 'Inclusion updated!');
          edit_inclusion_s_form.reset();
          get_inclusions();
      }
  }

  xhr.send(data);
}


function rem_inclusion(val){
  if(confirm('Are you sure you want to remove this inclusion?')){

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/inclusions_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  
    xhr.onload = function(){
        if(this.responseText == 1){
            alert('success', 'Inclusion removed!');
            get_inclusions();
        }
        else if(this.responseText == 'facility_added'){
            alert('error', 'Inclusion is existing in Facilities, cannot be removed!');
        }
        else{
            alert('error', 'Server down!');
        }
    }
  
    xhr.send('rem_inclusion='+val);
  }

}

window.onload = function(){
  get_inclusions();
}
