// document�Ɩ��񏑂��̂����邢�̂�$�ɒu������
var $ = document; 
var $form = $.querySelector('form');// jQuery�� $("form")����

//jQuery��$(function() { ����(�����������ɂ͈Ⴄ)
$.addEventListener('DOMContentLoaded', function() {
    //�摜�t�@�C���v���r���[�\��
    //  jQuery�� $('input[type="file"]')����
    // addEventListener�� on("change", function(e){}) ����
    $.querySelector('input[type="file"]').addEventListener('change', function(e) {
        var file = e.target.files[0],
               reader = new FileReader(),
               $preview =  $.querySelector(".preview"), // jQuery�� $(".preview")����
               t = this;

        // �摜�t�@�C���ȊO�̏ꍇ�͉������Ȃ�
        if(file.type.indexOf("image") < 0){
          return false;
        }

        reader.onload = (function(file) {
          return function(e) {
             //jQuery��$preview.empty(); ����
             while ($preview.firstChild) $preview.removeChild($preview.firstChild);

            // img�^�O���쐬
            var img = document.createElement( 'img' );
            img.setAttribute('src',  e.target.result);
            img.setAttribute('width', '150px');
            img.setAttribute('title',  file.name);
            // img�^�O��$previe�̒��ɒǉ�
            $preview.appendChild(img);
          }; 
        })(file);

        reader.readAsDataURL(file);
    }); 
});