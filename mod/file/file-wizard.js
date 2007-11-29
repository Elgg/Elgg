/**
* Variable to configure if the windows keep open or not
*/
var file_wizard_keep_open = true;

/**
 * Called to insert a file string
 * @param string edid input_field id
 * @param string err_msg error message
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function insertFile(edid,fileid){
  var text = '{{file:'+fileid+'}}';
  opener.addResource(edid,text,opener);
  if(!file_wizard_keep_open){
    window.close();
  }
}
/**
 * Called to insert a file string, removing all other content
 * @param string edid input_field id
 * @param string err_msg error message
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
function insertFileBrutal(edid,fileid){
  var text = '{{file:'+fileid+'}}';
  opener.setResource(edid,text,opener);
  if(!file_wizard_keep_open){
    window.close();
  }
}
