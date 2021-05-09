# Changelog
In here, I write the changelog for the project.

<!-- 

STANDARD NEW VERSION:

## x.y.z - YYYY-MM-DD
### Added
- a
- b
- c

### Changed
- a
- b
- c

### Removed
- a
- b
- c

### Fixed
- a
- b
- c

-->

<!-- 
## 0.5.0-alpha - 2021/05/xx

### Added
- values.js

### Fixed
- Refactored register.js 
-->



## 0.4.2-alpha - 2021/05/08

### Fixed
- Made JWT only accessible in cookie instead of the session too



## 0.4.1-alpha - 2021/05/08

### Fixed
- Page protection where payload.user was not set.
- JSON Web Token set in JavaScript from the cookie instead of the session.



## 0.4.0-alpha - 2021/05/08

### Added
- Account verification through an email.



## 0.3.0-alpha - 2021/05/08

### Added
- Client JSON Web Token validation, not allowing the user to view the dashboard without a valid token

### Fixed
- Database USERS scheme.
- Fixed bug where cookie set on "localhost" made it seem like everything worked



## 0.2.0-alpha - 2021/05/04
This version mostly includes adding the UI.

### Added
- Register account UI.
- Login account UI.
- Dashboard page.

### Fixed
- Refactored JavaScript code.



## 0.1.0-alpha - 2021/04/27
Note: I only started writing this changelog after I created the accounts system, which is why this version includes a lot of features. Every feature in here can only be used through software like Postman when it comes to any not GET request since I do not have created the UI yet.

### Added
- One can create an account.
- One can login to a created account.
- One can update and delete their own account.
- One can read all users (not a separate user, but all users in one request).
- JSON Web Tokens are generated on login.
