@extends('components.layouts.app')

@section('title', 'Terms & Conditions | Wonegig')
@section('meta_title', 'Terms & Conditions - Wonegig')
@section('meta_description', 'Read Wonegig’s terms and conditions for using our platform.')

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
            <h1 class="text-4xl font-bold text-white tracking-wide">Terms & Conditions</h1>
        </div>
    </section>
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-3xl policy-content">
            <p>Effective Date: January 1, 2025</p>
            <h2>1. Acceptance of Terms</h2>
            <p>
                By accessing or using Wonegig, you agree to be bound by these Terms & Conditions and our Privacy Policy.
            </p>
            <h2>2. User Accounts</h2>
            <ul class="list-disc pl-6">
                <li>You must provide accurate information and keep your account secure.</li>
                <li>You are responsible for all activities under your account.</li>
            </ul>
            <h2>3. Platform Usage</h2>
            <ul class="list-disc pl-6">
                <li>Do not use Wonegig for unlawful or fraudulent activities.</li>
                <li>Respect other users and intellectual property rights.</li>
            </ul>
            <h2>4. Payments & Fees</h2>
            <p>
                All payments are processed securely. Fees are transparently displayed before transactions.
            </p>
            <h2>5. Dispute Resolution</h2>
            <p>
                Disputes will be handled according to our Dispute and Chargeback Protection Policy.
            </p>
            <h2>6. Termination</h2>
            <p>
                We reserve the right to suspend or terminate accounts that violate these terms.
            </p>
            <h2>7. Changes to Terms</h2>
            <p>
                We may update these Terms & Conditions at any time. Continued use of Wonegig constitutes acceptance of the new terms.
            </p>
            <h2>8. Contact</h2>
            <p>
                For questions, contact <a href="mailto:support@wonegig.com" class="text-primary underline">support@wonegig.com</a>.
            </p>
        </div>
    </section>
</div>
@endsection