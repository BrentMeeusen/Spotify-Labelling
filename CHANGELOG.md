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

<!-- ## 0.x.x-alpha - 2021/x/x

### Added
- Playlist size limit because the server can't handle over 2000 tracks
- Limit to label text length

### Fixed
- Caught Spotify API response errors
- A minor bug that has to do with query parameters
- Buttons no longer send requests until the last request has received a response -->



## 0.10.2-alpha - 2021/06/28

### Fixed
- Showing labels would stop after public label is not owned
- Authorisation tokens wouldn't be stored completely
- Started waiting for Spotify API to prevent 419 and 428 errors



## 0.10.1-alpha - 2021/06/21

### Added
- State token for Spotify to make it more secure

### Fixed
- Refactored Your Songs page
- Refactored CSS files
- Moved AddedAt timestamp column from Tracks to Tracks_to_users



## 0.10.0-alpha - 2021/06/19

### Added
- Creating a new payload from the database values as soon as the token is required so we are up-to-date
- Made user grant access to the application on login
- Load in user playlists
- Import all songs from selected playlist into the database
- Redirecting to login screen when the token expires and a request is made
- Landing page

### Changed
- Refactored tables and table creation
- Removed and re-added all tables, with it removing all existing data

### Fixed
- Conditions for TRUE being too strict (as 1 may also work in some specific situations)
- Logout including a file that was not found bug



## 0.9.1-alpha - 2021/06/11

### Fixed
- Verifying method that did not work with the new interface yet
- Errors on verifying will now be returned to the user



## 0.9.0-alpha - 2021/06/11

### Added
- Showing labels on the page
- Edit labels
- Delete labels

### Changed
- Allowed deleting accounts when labels are created

### Fixed
- Logging in, even when account is not verified yet



## 0.8.0-alpha - 2021/06/09

### Added
- Your labels layout
- Create label
- Can add labels through the user interface



## 0.7.1-alpha - 2021/05/12

### Changed
- Clicking on title now redirects to dashboard instead of the current page



## 0.7.0-alpha - 2021/05/12

### Added
- Your songs page
- Your labels page
- Your playlists page
- How it works page
- Navigation menu
- Autofills form when new data is received if it's set
- Logout button

### Changed
- Additional password rule on password update
- Additional input check on account update
- Create a new JWT on updating account



## 0.6.1-alpha - 2021/05/10

### Added
- Waits for response before the next request can be made

### Changed
- Registering password login rules
- Must insert account password in order to delete account



## 0.6.0-alpha - 2021/05/10

### Added
- Delete your account
- Logout mechanism



## 0.5.1-alpha - 2021/05/09

### Changed
- Return messages from the database



## 0.5.0-alpha - 2021/05/09

### Added
- values.js
- Your Account page
- Update your account

### Fixed
- Refactored register.js 



## 0.4.2-alpha - 2021/05/08

### Fixed
- Made JWT only accessible in cookie instead of the session too



## 0.4.1-alpha - 2021/05/08

### Fixed
- Page protection where payload.user was not set
- JSON Web Token set in JavaScript from the cookie instead of the session



## 0.4.0-alpha - 2021/05/08

### Added
- Account verification through an email



## 0.3.0-alpha - 2021/05/08

### Added
- Client JSON Web Token validation, not allowing the user to view the dashboard without a valid token

### Fixed
- Database USERS scheme
- Fixed bug where cookie set on "localhost" made it seem like everything worked



## 0.2.0-alpha - 2021/05/04
This version mostly includes adding the UI.

### Added
- Register account UI
- Login account UI
- Dashboard page

### Fixed
- Refactored JavaScript code



## 0.1.0-alpha - 2021/04/27
Note: I only started writing this changelog after I created the accounts system, which is why this version includes a lot of features. Every feature in here can only be used through software like Postman when it comes to any not GET request since I do not have created the UI yet.

### Added
- One can create an account
- One can login to a created account
- One can update and delete their own account
- One can read all users (not a separate user, but all users in one request)
- JSON Web Tokens are generated on login
