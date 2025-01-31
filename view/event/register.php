<?php include __DIR__ . '/../include/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h4 class="text-center mb-4">
                New Registration for
                <?php echo $event['name']; ?>
            </h4>
            <?php if (!empty($_SESSION['errors'])) : ?>
                <div class="alert alert-danger p-2 pb-0">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error) : ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form
                action="<?= BASE_URL ?>events/register/<?php echo htmlspecialchars($event['id']); ?>"
                method="POST"
                class="shadow p-4 rounded bg-light"
            >
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input
                                type="text"
                                class="form-control"
                                id="fullName"
                                name="name"
                                placeholder="User full name"
                                required
                        >
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="User email address"
                                required
                        >
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-1">
                    <a href="<?= BASE_URL ?>events" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary me-2">Register</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>



