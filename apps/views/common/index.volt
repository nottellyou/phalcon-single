<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>{% block title %}{% endblock %}</title>
        {% block header_style %}{% endblock %}
        {% block header_js %}{% endblock %}
    </head>
    <body>
        {% block body %}{% endblock %}  
        {% block body_js %}{% endblock %}   
    </body>
</html>