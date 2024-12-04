let add_facility_form = document.getElementById('add_facility_form');

  add_facility_form.addEventListener('submit', function(e){
      e.preventDefault();
      add_facility();
  });

  function add_facility()
  {
    let data = new FormData();
    data.append('add_facility', '');
    data.append('name', add_facility_form.elements['name'].value);
    data.append('area', add_facility_form.elements['area'].value);
    data.append('price', add_facility_form.elements['price'].value);
    data.append('quantity', add_facility_form.elements['quantity'].value);
    data.append('desc', add_facility_form.elements['desc'].value);
      
    let facilities = [];
    add_facility_form.elements['inclusions'].forEach(el => {
        if(el.checked){
            facilities.push(el.value);
        }
    });
    data.append('inclusions', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);

    xhr.onload  = function(){
      var myModal = document.getElementById('add-facility');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      if(this.responseText == 1)
        {
          alert('success', 'New facility added!');
          add_facility_form.reset();
          get_all_facilities();
        }
      else{
        alert('error', 'Server Down!');
      }
    }
    xhr.send(data);
  }

  function get_all_facilities()
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload  = function(){
        document.getElementById('facility-data').innerHTML = this.responseText;
    }

    xhr.send('get_all_facilities');
  }

  let edit_facility_form = document.getElementById('edit_facility_form');

  function edit_details(id)
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload  = function(){
        let data = JSON.parse(this.responseText);
        
        edit_facility_form.elements['name'].value = data.facilitydata.name;
        edit_facility_form.elements['area'].value = data.facilitydata.area;
        edit_facility_form.elements['price'].value = data.facilitydata.price;
        edit_facility_form.elements['quantity'].value = data.facilitydata.quantity;
        edit_facility_form.elements['desc'].value = data.facilitydata.description;
        edit_facility_form.elements['facility_id'].value = data.facilitydata.id;

        edit_facility_form.elements['inclusions'].forEach(el => {
            if(data.facilities.includes(Number(el.value))){
                el.checked = true;
            }
        });
      }
    xhr.send('get_facility=' +id);
  }

  edit_facility_form.addEventListener('submit', function(e){
    e.preventDefault();
    submit_edit_facility();
  });

  function submit_edit_facility()
  {
    let data = new FormData();
    data.append('edit_facility', '');
    data.append('facility_id', edit_facility_form.elements['facility_id'].value);
    data.append('name', edit_facility_form.elements['name'].value);
    data.append('area', edit_facility_form.elements['area'].value);
    data.append('price', edit_facility_form.elements['price'].value);
    data.append('quantity', edit_facility_form.elements['quantity'].value);
    data.append('desc', edit_facility_form.elements['desc'].value);
    
    let inclusions = [];
    edit_facility_form.elements['inclusions'].forEach(el => {
        if(el.checked){
            inclusions.push(el.value);
        }
    });
    data.append('inclusions', JSON.stringify(inclusions));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);

    xhr.onload  = function(){
      var myModal = document.getElementById('edit-facility');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      if(this.responseText == 1)
        {
          alert('success', 'Facility data edited!');
          edit_facility_form.reset();
          get_all_facilities();
      }
      else{
          alert('error', 'Server Down!');
      }
    }
    xhr.send(data);
  }

  function toggle_status(id, val)
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload  = function(){
      if(this.responseText == 1)
        {
          alert('success', 'Status toggled!');
          get_all_facilities();
        }
      else{
          alert('error', 'Server Down!');
      }
    }
    xhr.send('toggle_status=' +id+ '&value=' +val);
  }

  let add_image_form = document.getElementById('add_image_form');

  add_image_form.addEventListener('submit',function(e){
      e.preventDefault();
      add_image();
  });

  function add_image()
  {
    let data = new FormData();
    data.append('image',add_image_form.elements['image'].files[0]);
    data.append('facility_id',add_image_form.elements['facility_id'].value);
    data.append('add_image','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/facilities_crud.php", true);

    xhr.onload = function()
    {
      if(this.responseText == 'inv_img'){
        alert('error', 'Only JPG WEBP or PNG images are allowed!', 'image-alert');
      }
      else if(this.responseText == 'inv_size'){
        alert('error', 'Image should be less than 10MB!', 'image-alert');
      }
      else if(this.responseText == 'upd_failed'){
        alert('error', 'Image upload failed. Server Down!', 'image-alert');
      }
      else{
        alert('success', 'New image added!', 'image-alert');
        facility_images(add_image_form.elements['facility_id'].value, document.querySelector("#facility-images .modal-title").innerText);
        add_image_form.reset();
      }
    }
    xhr.send(data);
  }

  function facility_images(id,rname)
  {
    document.querySelector("#facility-images .modal-title").innerText =rname;
    add_image_form.elements['facility_id'].value = id;
    add_image_form.elements['image'].value = '';

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
      document.getElementById('facility-image-data').innerHTML = this.responseText;
    }
    xhr.send('get_facility_images=' + id);
  }

  function rem_image(img_id, facility_id)
  {
    if(confirm('Are you sure you want to remove this image?')){
      let data = new FormData();
      data.append('image_id', img_id);
      data.append('facility_id', facility_id);
      data.append('rem_image','');

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/facilities_crud.php", true);

      xhr.onload = function()
      {
        if(this.responseText == 1){
          alert('success', 'Image Removed!', 'image-alert');
          facility_images(facility_id, document.querySelector("#facility-images .modal-title").innerText);
        }
        else{
          alert('error', 'Image remove failed!', 'image-alert');
        }
      }
      xhr.send(data);
    }
  }

  function thumb_image(img_id, facility_id)
  {
    let data = new FormData();
    data.append('image_id', img_id);
    data.append('facility_id', facility_id);
    data.append('thumb_image','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/facilities_crud.php", true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success', 'Image Thumbnail Changed!', 'image-alert');
        facility_images(facility_id, document.querySelector("#facility-images .modal-title").innerText);
      }
      else{
        alert('error', 'Thumbnail remove failed!', 'image-alert');
      }
    }
    xhr.send(data);
  }

  function remove_facility(facility_id)
  {
    if(confirm('Are you sure you want to remove this facility?'))
    {
      let data = new FormData();
      data.append('facility_id', facility_id);
      data.append('remove_facility','');

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/facilities_crud.php", true);

      xhr.onload = function()
      {
        if(this.responseText == 1){
          alert('success', 'Facility Removed!');
          get_all_facilities();
        }
        else{
          alert('error', 'Facility remove failed!');
        }
      }
      xhr.send(data);
    }
  }

  window.onload = function()
  {
    get_all_facilities();
  }