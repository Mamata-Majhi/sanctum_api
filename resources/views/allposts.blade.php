<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Table Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-dark">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h3 class="text-center mb-4">All posts </h3>
                <div class="mb-3">
                    <a href="/addpost" class="btn btn-success btn md">Add New</a>
                    <button class="btn btn-danger btn-md" id="logoutBtn">LogOut</button>
                </div>
                <div id="postContainer">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Single Post</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelector("#logoutBtn").addEventListener('click', function() {
            const token = localStorage.getItem('api_token');
            fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = "http://localhost:8000/";
                });
        });

        function loadData() {
            const token = localStorage.getItem('api_token');

            fetch('/api/posts', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.data.posts);
                    var allpost = data.data.posts;
                    const postContainer = document.querySelector("#postContainer");
                    var tabledata = `<table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">View</th>
                                <th scope="col">Update</th>
                                <th scope="col">Delete</th>

                            </tr>
                        </thead>`;
                        allpost.forEach(post => {
                            tabledata += `<tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>${post.name}</td>
                                <td>${post.description}</td>
                                <td><img src="/uploads/${post.image}" alt="Image" style="width:150px;" class="img-thumbnail"></td>
                                <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-bs-post="${post.id}">View</button></td>
                                <td><button type="button" class="btn btn-success btn-sm">Update</button></td>
                                <td><button type="button" class="btn btn-danger btn-sm">Delete</button></td>
                            </tr>
                            {{-- Images should be called from backend as you are an backend developer you should
                         use an static images , everything should be dynamic ,Thank You! --}}

                        </tbody>`;

                            
                        });
                        
                    tabledata += `</table>`;
                    postContainer.innerHTML = tabledata;
                });

        }
        loadData();
    </script>

</body>

</html>
