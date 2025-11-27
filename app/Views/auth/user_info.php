<h1 align="center">User Info</h1><br>

<?php if (!empty($user)): ?>
<div class="card">
    <div class="card-body">
    <table class="table">
        <tbody>
        <tr>
            <td style="background-color:#F8F8F8;width:200px;"><strong>ID</strong></td>
            <td><?= esc($user['id']) ?></td>
        </tr>
        <tr>
            <td style="background-color:#F8F8F8;"><strong>User Name</strong></td>
            <td> <?= esc($user['username']) ?></td>
        </tr>
        <tr>
            <td style="background-color:#F8F8F8;"><strong>First Name</strong></td>
            <td> <?= esc($user['firstname']) ?></td>
        </tr>
        <tr>
            <td style="background-color:#F8F8F8;"><strong>Last Name</strong></td>
            <td> <?= esc($user['lastname']) ?></td>
        </tr>
        <tr>
            <td style="background-color:#F8F8F8;"><strong>Email</strong></td>
            <td> <?= esc($user['email']) ?></td>
        </tr>
        <tr>
            <td style="background-color:#F8F8F8;"><strong>Phone</strong></td>
            <td> <?= esc($user['phone']) ?></td>
        </tr>
        </tbody>
    </table>
</div>
</div>
<?php else: ?>
    <p>User not found.</p>
<?php endif ?>