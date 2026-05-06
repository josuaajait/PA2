@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold mb-3">About Caldera</h1>
            <p class="lead text-muted">Experience the perfect blend of culinary delight and refreshing pool experience.</p>
            <p>Caldera Resto and Pool berdiri sejak tahun 2020 dengan konsep restoran keluarga yang dilengkapi fasilitas kolam renang. Berawal dari keinginan menciptakan tempat berkumpul yang nyaman untuk keluarga dan teman, Caldera kini telah menjadi salah satu destinasi favorit di kota.</p>
            <p>Kami berkomitmen untuk memberikan pelayanan terbaik, makanan berkualitas, dan pengalaman yang tak terlupakan bagi setiap pengunjung.</p>
        </div>
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=600" class="img-fluid rounded-4 shadow-lg" alt="About Caldera">
        </div>
    </div>

    <div class="row text-center g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Delicious Cuisine</h5>
                <p class="text-muted">Authentic Indonesian and international dishes prepared by expert chefs</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <i class="fas fa-swimmer fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Refreshing Pool</h5>
                <p class="text-muted">Clean and well-maintained swimming pool for family fun</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <i class="fas fa-music fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Live Entertainment</h5>
                <p class="text-muted">Regular live music and special events to enhance your experience</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Our Vision & Mission</h2>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <i class="fas fa-eye fa-2x text-primary mb-3"></i>
                <h5 class="fw-bold">Vision</h5>
                <p class="text-muted">Menjadi destinasi kuliner dan rekreasi terbaik di Indonesia yang dikenal dengan pelayanan prima dan pengalaman tak terlupakan.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <i class="fas fa-bullseye fa-2x text-primary mb-3"></i>
                <h5 class="fw-bold">Mission</h5>
                <ul class="text-muted">
                    <li>Menyediakan makanan berkualitas dengan harga terjangkau</li>
                    <li>Memberikan pelayanan terbaik kepada pelanggan</li>
                    <li>Menciptakan suasana yang nyaman dan menyenangkan</li>
                    <li>Mengembangkan inovasi menu dan fasilitas secara berkelanjutan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Page header banner */
    .about-hero {
        background: linear-gradient(135deg, #1c3451 0%, #01516e 100%);
        padding: 50px 0 40px;
        margin-bottom: 0;
        color: white;
    }

    /* Section title underline gold */
    h1, h2 {
        font-family: 'Playfair Display', serif;
        color: #1c3451;
    }

    h1.display-4 {
        color: #1c3451;
        font-size: 2.4rem;
    }

    /* Cards */
    .card {
        border-radius: 16px !important;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(28,52,81,0.12) !important;
    }

    /* Icon color override to navy/gold */
    .fa-utensils, .fa-swimmer, .fa-music,
    .fa-eye, .fa-bullseye {
        color: #c1a067 !important;
    }

    /* Section divider */
    .section-divider {
        width: 50px;
        height: 3px;
        background: #c1a067;
        margin: 12px auto 24px;
        border-radius: 2px;
    }

    /* Vision Mission heading */
    .row .col-12.text-center h2::after {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background: #c1a067;
        margin: 12px auto 0;
        border-radius: 2px;
    }

    /* Feature card icon wrapper */
    .card .fas {
        display: block;
    }

    /* Image */
    .img-fluid.rounded-4 {
        border: 4px solid #e8e0d0;
    }

    /* Lead text */
    .lead {
        color: #5a7a99 !important;
        font-weight: 500;
    }
</style>
@endpush