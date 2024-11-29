
  function get_reservations()
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_reservations_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload  = function(){
        document.getElementById('table-data').innerHTML = this.responseText;
    }

    xhr.send('get_reservations');
  }

  function remove_user(user_id)
  {
    if(confirm('Are you sure you want to remove this User?'))
    {
      let data = new FormData();
      data.append('user_id', user_id);
      data.append('remove_user','');

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/users_crud.php", true);

      xhr.onload = function()
      {
        if(this.responseText == 1){
          alert('success', 'User Removed!');
          get_users();
        }
        else{
          alert('error', 'User remove failed!');
        }
      }
      xhr.send(data);
    }
  }

  function search_user(username)
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload  = function(){
        document.getElementById('users-data').innerHTML = this.responseText;
    }

    xhr.send('search_user&name='+username);
  }

  window.onload = function()
  {
    get_reservations();
  }