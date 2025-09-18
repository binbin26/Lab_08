<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Đăng ký người dùng</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="card-title mb-3">Đăng ký</h3>
      <form id="userForm" method="post" action="process.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input id="username" name="username" type="text" class="form-control" placeholder="Nhập username">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" name="password" type="password" class="form-control" placeholder="Mật khẩu (≥6 ký tự)">
        </div>

        <div class="mb-3">
          <label for="age" class="form-label">Age: <span id="ageValue">55</span></label>
          <input id="age" name="age" type="range" min="0" max="100" value="55" class="form-range">
        </div>

        <div class="mb-3">
          <label class="form-label">Hobby</label><br>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hobby[]" value="Thể thao" id="h1">
            <label class="form-check-label" for="h1">Thể thao</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hobby[]" value="Âm nhạc" id="h2">
            <label class="form-check-label" for="h2">Âm nhạc</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hobby[]" value="Nghệ thuật" id="h3">
            <label class="form-check-label" for="h3">Nghệ thuật</label>
          </div>
        </div>

        <div class="mb-3">
          <label for="country" class="form-label">Country</label>
          <select id="country" name="country" class="form-select">
            <option value="Viet Nam">Việt Nam</option>
            <option value="Hoa Ky">Hoa Kỳ</option>
            <option value="Trung Quoc">Trung Quốc</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-secondary">Cancel</button>
      </form>
    </div>
  </div>

  <!-- Hiển thị kết quả -->
  <div id="resultBox" class="mt-4"></div>

  <!-- Danh sách người dùng đã lưu -->
  <div class="mt-4">
    <button id="showUsers" class="btn btn-success">Hiển thị danh sách người dùng</button>
    <div id="userList" class="mt-3"></div>
  </div>
</div>

<script>
// cập nhật nhãn tuổi
const ageInput = document.getElementById('age');
const ageValue = document.getElementById('ageValue');
ageInput.addEventListener('input', () => ageValue.textContent = ageInput.value);

// submit form qua fetch
document.getElementById('userForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  const res = await fetch(this.action, {
    method: 'POST',
    body: formData
  });
  const data = await res.json();

  const resultBox = document.getElementById('resultBox');
  resultBox.innerHTML = '';

  if (data.errors) {
    // hiển thị lỗi
    let html = '<div class="alert alert-danger"><ul>';
    data.errors.forEach(err => {
      html += `<li>${err}</li>`;
    });
    html += '</ul></div>';
    resultBox.innerHTML = html;
  } else {
    // hiển thị kết quả
    resultBox.innerHTML = `
      <div class="alert alert-success">
        <h5>Người dùng mới:</h5>
        <p><b>Username:</b> ${data.username}</p>
        <p><b>Password (masked):</b> ${data.password_mask}</p>
        <p><b>Age:</b> ${data.age}</p>
        <p><b>Hobby:</b> ${data.hobby.length ? data.hobby.join(', ') : 'Không chọn'}</p>
        <p><b>Country:</b> ${data.country}</p>
      </div>
    `;

    // lưu vào localStorage
    let users = JSON.parse(localStorage.getItem('users') || '[]');
    users.push(data);
    localStorage.setItem('users', JSON.stringify(users));
  }
});

// hiển thị danh sách người dùng
document.getElementById('showUsers').addEventListener('click', function() {
  let users = JSON.parse(localStorage.getItem('users') || '[]');
  let html = '';
  if (!users.length) {
    html = '<div class="alert alert-warning">Chưa có người dùng nào.</div>';
  } else {
    html = '<div class="list-group">';
    users.forEach((u, i) => {
      html += `
        <div class="list-group-item">
          <b>#${i+1}</b> - ${u.username}, Age: ${u.age}, Country: ${u.country}
        </div>`;
    });
    html += '</div>';
  }
  document.getElementById('userList').innerHTML = html;
});
</script>
</body>
</html>
