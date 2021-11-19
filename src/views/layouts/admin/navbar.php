<!DOCTYPE html>
<html lang="fr">
{{ @component("head") }}
<body>
{{ @component("header") }}
{{ @component("admin_navbar") }}
<div class="container">
    <div class="content">
        {{flashMessages}}
        {{content}}
    </div>
</div>
{{ @component("footer") }}
</body>