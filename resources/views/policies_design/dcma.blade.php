@extends('components.layouts.app')

@section('title', 'DMCA Policy | Wonegig')
@section('meta_title', 'DMCA Policy - Wonegig')
@section('meta_description', 'Wonegig’s DMCA policy for copyright complaints and takedown requests.')

@push('styles')
<style>
    .policy-hero-bg { /* ...same as above... */ }
    .policy-hero-bg::before { /* ...same as above... */ }
    .policy-hero-content { /* ...same as above... */ }
    .policy-content h2 { /* ...same as above... */ }
    .policy-content p, .policy-content ul { /* ...same as above... */ }
</style>
@endpush

@section('content')
<div>
    <section class="policy-hero-bg">
        <div class="policy-hero-content text-center w-full">
            <h1 class="text-4xl font-bold text-white tracking-wide">DMCA Policy</h1>
        </div>
    </section>
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-3xl policy-content">
            <p>Effective Date: January 1, 2025</p>
            <h2>1. Copyright Infringement Notification</h2>
            <p>
                If you believe your copyrighted work has been used on Wonegig in a way that constitutes copyright infringement, please notify us with the following information:
            </p>
            <ul class="list-disc pl-6">
                <li>Your contact information</li>
                <li>A description of the copyrighted work</li>
                <li>The URL or location of the infringing material</li>
                <li>A statement of good faith belief</li>
                <li>Your electronic or physical signature</li>
            </ul>
            <h2>2. Takedown Procedure</h2>
            <p>
                Upon receiving a valid DMCA notice, we will promptly remove or disable access to the infringing content and notify the user responsible.
            </p>
            <h2>3. Counter-Notification</h2>
            <p>
                If you believe your content was removed in error, you may submit a counter-notification. We will review and, if appropriate, restore the content.
            </p>
            <h2>4. Contact</h2>
            <p>
                DMCA notices should be sent to <a href="mailto:dmca@wonegig.com" class="text-primary underline">dmca@wonegig.com</a>.
            </p>
        </div>
    </section>
</div>
@endsection