<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Manage Enrollees</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="manage-enrollees">
      <nav class="sidebar" role="navigation" aria-label="Main navigation">
        <div class="sidebar-logo">
          <img class="logo-icon" src="img/group-8.png" alt="Logo" />
        </div>
        <ul class="sidebar-menu">
          <li class="sidebar-menu-item">
            <a href="#" class="sidebar-link" aria-label="Home">
              <img src="img/group-9.png" alt="" />
            </a>
          </li>
        </ul>
        <div class="sidebar-logout">
          <button class="logout-btn" aria-label="Logout">
            <img class="logout-icon" src="img/vector-6.svg" alt="" />
          </button>
        </div>
      </nav>
      <header class="top-header" role="banner">
        <h1 class="page-title">Manage Enrolles</h1>
        <div class="header-search">
          <label for="course-search" class="visually-hidden">Search courses</label>
          <input
            type="search"
            id="course-search"
            class="search-input"
            placeholder="Search courses"
            aria-label="Search courses"
          />
          <button class="search-btn" type="button" aria-label="Search">
            <img class="search-icon" src="img/vector-4.svg" alt="" />
          </button>
        </div>
        <div class="header-actions">
          <button class="notification-btn" aria-label="Notifications">
            <img class="notification-icon" src="img/vector-3.svg" alt="" />
          </button>
          <button class="profile-btn" aria-label="User profile">
            <div class="profile-icon">
              <img class="profile-avatar-body" src="img/vector.svg" alt="" />
              <img class="profile-avatar-head" src="img/vector-2.svg" alt="" />
            </div>
          </button>
        </div>
      </header>
      <main class="main-content" role="main">
        <div class="content-header">
          <div class="search-section">
            <label for="main-search" class="visually-hidden">Search</label>
            <input
              type="search"
              id="main-search"
              class="main-search-input"
              placeholder="Search"
              aria-label="Search enrollees"
            />
            <button class="main-search-btn" type="button" aria-label="Search">
              <img class="main-search-icon" src="img/vector-5.svg" alt="" />
            </button>
          </div>
          <button class="add-enrollees-btn" type="button">
            <img class="add-icon" src="img/image.svg" alt="" />
            <span>Add Enrollees</span>
          </button>
        </div>
        <section class="enrollees-table-section" aria-labelledby="enrollees-heading">
          <div class="table-container">
            <table class="enrollees-table" role="table" aria-label="Enrollees data">
              <thead>
                <tr>
                  <th scope="col" class="table-header">Name</th>
                  <th scope="col" class="table-header">Affiliation</th>
                  <th scope="col" class="table-header">Enrollee ID</th>
                  <th scope="col" class="table-header">Status</th>
                  <th scope="col" class="table-header">Courses Enrolled</th>
                  <th scope="col" class="table-header visually-hidden">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-row">
                  <td class="table-cell" data-label="Name">Jane Doe</td>
                  <td class="table-cell" data-label="Affiliation">World Vision</td>
                  <td class="table-cell" data-label="Enrollee ID">1234-5678-9</td>
                  <td class="table-cell" data-label="Status">Active</td>
                  <td class="table-cell" data-label="Courses Enrolled">Course 101</td>
                  <td class="table-cell table-actions" data-label="Actions">
                    <div class="action-buttons">
                      <button class="action-btn view-btn" type="button" aria-label="View Jane Doe details">View</button>
                      <button class="action-btn edit-btn" type="button" aria-label="Edit Jane Doe">Edit</button>
                      <button class="action-btn remove-btn" type="button" aria-label="Remove Jane Doe">Remove</button>
                    </div>
                  </td>
                </tr>
                <tr class="table-row">
                  <td class="table-cell" data-label="Name">Jane Doe</td>
                  <td class="table-cell" data-label="Affiliation">World Vision</td>
                  <td class="table-cell" data-label="Enrollee ID">1234-5678-9</td>
                  <td class="table-cell" data-label="Status">Active</td>
                  <td class="table-cell" data-label="Courses Enrolled">Course 101</td>
                  <td class="table-cell table-actions" data-label="Actions">
                    <div class="action-buttons">
                      <button class="action-btn view-btn" type="button" aria-label="View Jane Doe details">View</button>
                      <button class="action-btn edit-btn" type="button" aria-label="Edit Jane Doe">Edit</button>
                      <button class="action-btn remove-btn" type="button" aria-label="Remove Jane Doe">Remove</button>
                    </div>
                  </td>
                </tr>
                <tr class="table-row">
                  <td class="table-cell" data-label="Name">Jane Doe</td>
                  <td class="table-cell" data-label="Affiliation">World Vision</td>
                  <td class="table-cell" data-label="Enrollee ID">1234-5678-9</td>
                  <td class="table-cell" data-label="Status">Active</td>
                  <td class="table-cell" data-label="Courses Enrolled">Course 101</td>
                  <td class="table-cell table-actions" data-label="Actions">
                    <div class="action-buttons">
                      <button class="action-btn view-btn" type="button" aria-label="View Jane Doe details">View</button>
                      <button class="action-btn edit-btn" type="button" aria-label="Edit Jane Doe">Edit</button>
                      <button class="action-btn remove-btn" type="button" aria-label="Remove Jane Doe">Remove</button>
                    </div>
                  </td>
                </tr>
                <tr class="table-row">
                  <td class="table-cell" data-label="Name">Jane Doe</td>
                  <td class="table-cell" data-label="Affiliation">World Vision</td>
                  <td class="table-cell" data-label="Enrollee ID">1234-5678-9</td>
                  <td class="table-cell" data-label="Status">Active</td>
                  <td class="table-cell" data-label="Courses Enrolled">Course 101</td>
                  <td class="table-cell table-actions" data-label="Actions">
                    <div class="action-buttons">
                      <button class="action-btn view-btn" type="button" aria-label="View Jane Doe details">View</button>
                      <button class="action-btn edit-btn" type="button" aria-label="Edit Jane Doe">Edit</button>
                      <button class="action-btn remove-btn" type="button" aria-label="Remove Jane Doe">Remove</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
