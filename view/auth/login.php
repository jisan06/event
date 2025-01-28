<?php include __DIR__ . '/../include/header.php'; ?>

<div class="container mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
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
                            <?php if (!empty($_SESSION['success'])) : ?>
                                <div class="alert alert-success p-2">
                                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>
                            <form method="post" action="/login">

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="email">Your Email</label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <label class="form-label" for="password">Password</label>
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            minlength="6"
                                            required
                                        />
                                    </div>
                                </div>

                                <div>
                                    <button
                                        class="btn btn-primary btn-lg"
                                        type="submit"
                                        data-mdb-button-init
                                        data-mdb-ripple-init
                                    >
                                        Login
                                    </button>
                                </div>

                            </form>

                        </div>
                        <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                            <img
                                src="https://www.mccormick.northwestern.edu/mechanical/images/graduate/me512-header-photo.jpg"
                                class="img-fluid"
                                alt="Sample image"
                            >

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
