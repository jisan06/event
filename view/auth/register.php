<?php include __DIR__ . '/../include/header.php'; ?>

<div class="container mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
            <div class="card text-black" style="border-radius: 25px;">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <h3>Create Your Account</h3>

                            <form method="post" action="/register">

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="form-control"
                                            required
                                        />
                                        <label class="form-label" for="name">Your Name</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control"
                                            required
                                    />
                                        <label class="form-label" for="email">Your Email</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            minlength="6"
                                            required
                                        />
                                        <label class="form-label" for="password">Password</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                        <input
                                            type="password"
                                            name="confirm_pass"
                                            id="confirm_pass"
                                            class="form-control"
                                            minlength="6"
                                            required
                                        />
                                        <label class="form-label" for="confirm_pass">Repeat your password</label>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">Register
                                    </button>
                                </div>

                            </form>

                        </div>
                        <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                            <img
                                src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
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
