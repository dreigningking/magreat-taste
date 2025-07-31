@extends('components.layouts.app')

@section('title', 'Dispute & Chargeback Protection Policy | Wonegig')
@section('meta_title', 'Dispute & Chargeback Protection Policy - Wonegig')
@section('meta_description', 'Learn how Wonegig protects users in case of disputes and chargebacks.')

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
            <h1 class="text-4xl font-bold text-white tracking-wide">Dispute & Chargeback Protection Policy</h1>
        </div>
    </section>
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-3xl policy-content">
            <p>Effective Date: January 1, 2025</p>
            <h2>1. Dispute Resolution</h2>
            <p>
                If a disagreement arises between a client and a freelancer, Wonegig offers a structured dispute resolution process. Both parties may submit evidence, and our independent panel will review and issue a decision within 48 hours.
            </p>
            <h2>2. Chargeback Protection</h2>
            <p>
                Wonegig’s escrow system protects both clients and freelancers. In the event of a chargeback, we will investigate and, if the work was delivered as agreed, defend the freelancer’s payment.
            </p>
            <h2>3. Filing a Dispute</h2>
            <ul class="list-disc pl-6">
                <li>Contact support within 7 days of project completion.</li>
                <li>Provide all relevant details and evidence.</li>
                <li>Our team will mediate and communicate the outcome.</li>
            </ul>
            <h2>4. Contact</h2>
            <p>
                For dispute or chargeback issues, email <a href="mailto:support@wonegig.com" class="text-primary underline">support@wonegig.com</a>.
            </p>
        </div>
    </section>
</div>
@endsection