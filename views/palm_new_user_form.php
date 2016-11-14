
        <!-- http://parsleyjs.org/ - v2.4.4 -->
        <script src="../js/parsley.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" />

<div style="max-width:80%; margin: 0 auto;">

<form id="newUserRegForm" data-parsley-validate="" action="backendadduser.php?mode=adduser" method="POST">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"  placeholder="Email Address" required="">
        <small id="emailHelp" class="form-text text-muted">We will protect your email address as if it were our own.</small>
      </div>
      <div class="form-group">
        <label for="first">Name</label>
        <input type="text" class="form-control" id="name" name="name"  placeholder="First name" required="">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="^([a-zA-Z0-9@*#]{8,30})$" required="">
        <small id="passwordHelp" class="form-text text-muted">Password must be between 8 and 30 characters.</small>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>

</form>
</div>