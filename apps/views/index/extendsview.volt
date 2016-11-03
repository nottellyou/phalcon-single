{% extends "common/index.volt" %}
{% block title %}HTML5 图片上传预览{% endblock %}

{# 注释：原文URL地址：http://www.sucaihuo.com/js/292.html #}
{% block header_style %}
<style>
            #photo{ width:100px; height:100px; margin:auto; margin-top:100px; background:#0cc; border-radius:100px;}
            #photo img{ width:100px; height:100px; border-radius:50px;}
</style>
{% endblock %}

{% block header_js %}
<script src="http://www.sucaihuo.com/jquery/2/292/demo/js/jquery.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $('#img').change(function() {
                    var file = this.files[0];
                    var r = new FileReader();
                    r.readAsDataURL(file);
                    $(r).load(function() {
                        $('#photo').html('<img src="' + this.result + '" alt="" />');
                    })
                })
            })

        </script>
{% endblock %}


{% block body %}
<h3>HTML5 图片上传预览</h3>
<input id="img" type="file" accept="image/*" />
<div id="photo"></div>
{% endblock %}