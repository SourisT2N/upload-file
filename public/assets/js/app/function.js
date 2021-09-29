function getIdFile()
{
    str = new URL(location.href).pathname.replace(/^\/+|\/+$/g,'');
    regex = /[a-zA-Z0-9]+$/i;
    return str.match(regex)[0];
}

function createButton(nameButton)
{
    return `<div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Current ${nameButton}</label>
                <input type="${nameButton}" name="current-${nameButton}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">New ${nameButton}</label>
                <input type="${nameButton}" name="new-${nameButton}" class="form-control" id="exampleInputPassword1">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword2" class="form-label">Re ${nameButton}</label>
                <input type="${nameButton}" name="re-${nameButton}" class="form-control" id="exampleInputPassword2">
              </div>`
              ;
}

function createMessage(message,status)
{
  return `<div class="alert alert-${status?'success':'danger'} text-center" role="alert">
    ${message}
  </div>`;
}

function checkAuth()
{
  setTimeout(() => {
    getAuth();
    checkAuth();
  },120000);
}

function getAuth()
{
  $.ajax({
    type: "POST",
    url: "/api/auth",
    dataType: 'json',
    xhrFields: {
      withCredentials: true
    }
  });
}