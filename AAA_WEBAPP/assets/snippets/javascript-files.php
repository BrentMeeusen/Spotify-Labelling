<!-- Load all files -->
<script src="/assets/js/api.js"></script>
<script src="/assets/js/html-js-form.js"></script>
<script src="/assets/js/big-popup.js"></script>
<script src="/assets/js/filter.js"></script>
<script src="/assets/js/jwt.js"></script>
<script src="/assets/js/lazy-loading.js"></script>
<script src="/assets/js/navigation.js"></script>
<script src="/assets/js/page-protect.js"></script>
<script src="/assets/js/password-verifier.js"></script>
<script src="/assets/js/popup.js"></script>
<script src="/assets/js/spotify.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="/assets/js/values.js"></script>

<!-- Load general file (which will be a minified version of all the files above that are general) -->
<script src="/assets/js/general.js"></script>

<!-- Set JWT -->
<?php if(isset($_COOKIE["jwt"])) { print('<script>Api.TOKEN = new JWT("' . $_COOKIE["jwt"] . '"); </script>'); } ?>
