'use strict';



/**
 * Do an ajax request
 *
 * @param {string} type - GET | POST | PUT | DELETE
 * @param {string} url - Url of the request
 * @param {function} callback - Function to call on callback with response
 * @param {string} data - Data to send to the server (form-urlencoded)
 */
function ajax_req(type, url, callback, data = null, error_handler = handle_error){
  let xhr = new XMLHttpRequest();
  if (type == 'GET' && data != null)
    url += '?' + data;
  xhr.open(type, url);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = () => {
    // console.log(xhr.status)
    switch (xhr.status) {
      case 200:
      case 201:
        if (xhr.responseText)
          callback(JSON.parse(xhr.responseText));
        break;
      default:
        error_handler(xhr.status);
    }
  };

  xhr.send(data);
}


/**
 * Default way to display an error
 *
 * @param {string} error
 */
function handle_error(error){
  let messages = {
    400: 'Bad request',
    401: 'Unauthorized',
    403: 'Forbidden',
    404: 'Not found',
    500: 'Internal server error',
    503: 'Unavailable'
  };

  console.log((error in messages) ? `${error}: ${messages[error]}` : `${error}`);
}


function printf (data) {
  // ajax_req(
  //   'GET',
  //   'php/request.php/test/',
  //   test,
  //   '&tab=' + 'crops'
  // );

  console.log("Response received:", data);
}
