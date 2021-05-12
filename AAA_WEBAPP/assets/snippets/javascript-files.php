<!-- Load all files -->
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/api.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/html-js-form.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/jwt.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/lazy-loading.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/navigation.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/page-protect.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/password-verifier.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/popup.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/theme.js"></script>
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/values.js"></script>

<!-- Load general file (which will be a minified version of all the files above that are general) -->
<script src="/Spotify Labelling/AAA_WEBAPP/assets/js/general.js"></script>

<!-- Set JWT -->
<?php if(isset($_COOKIE["jwt"])) { print('<script>Api.TOKEN = new JWT("' . $_COOKIE["jwt"] . '"); </script>'); } ?>
