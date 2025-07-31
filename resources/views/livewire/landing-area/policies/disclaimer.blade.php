<div>
    <section class="policy-hero-bg">
        <div class="policy-hero-content text-center w-full">
            <h1 class="text-4xl font-bold text-white tracking-wide">Website &amp; Platform Disclaimer</h1>
        </div>
    </section>
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-3xl policy-content">
            <h2>Wonegig — Website &amp; Platform Disclaimer</h2>
            <p>Please read this Disclaimer in full before using the Wonegig website, mobile apps, or any associated services (collectively "Wonegig" or "Platform"). By continuing to browse or interact with Wonegig you acknowledge that you have read, understood, and agree to the terms below. If you disagree with any part of this Disclaimer, discontinue use of the Platform immediately.</p>

            <h2>1. No Professional Advice</h2>
            <ul class="list-disc pl-6">
                <li><strong>Information Only.</strong> All content—articles, tutorials, videos, FAQs, and support materials—are provided for general information purposes. Nothing on Wonegig constitutes legal, tax, financial, accounting, employment, or other professional advice.</li>
                <li><strong>Consult Experts.</strong> You should consult appropriately qualified advisers before making decisions reliant on any information obtained through Wonegig.</li>
            </ul>

            <h2>2. Marketplace Facilitator — No Employment or Agency</h2>
            <p>Wonegig operates solely as an online venue that allows independent Clients and Freelancers to connect and contract.</p>
            <ul class="list-disc pl-6">
                <li><strong>No Employment Relationship.</strong> Wonegig is not an employer, joint-venturer, or partner of Users.</li>
                <li><strong>No Warranty of Performance.</strong> We do not guarantee that a particular Client will hire a Freelancer, that work will meet specific standards, or that payment will be made. All contracts are strictly between the participating Users.</li>
                <li><strong>Escrow Service.</strong> Escrow is provided for convenience; Wonegig is not a bank or money-service business and disclaims fiduciary duties except as expressly required by law.</li>
            </ul>

            <h2>3. Earnings &amp; Results Disclaimer</h2>
            <ul class="list-disc pl-6">
                <li><strong>Individual Outcomes Vary.</strong> Any income figures, success stories, or performance metrics displayed on Wonegig or in marketing materials are illustrative only. They do not represent typical results and should not be relied upon as promises of future earnings.</li>
                <li><strong>Risk of Loss.</strong> Freelancing and hiring carry inherent risks—including but not limited to non-payment, project cancellation, and reputational impact—for which Wonegig accepts no responsibility beyond the limited protections stated in our Terms &amp; Conditions.</li>
            </ul>

            <h2>4. Third-Party Content &amp; Links</h2>
            <ul class="list-disc pl-6">
                <li><strong>External Sites.</strong> Wonegig may link to third-party websites, tools, or resources. We neither control nor endorse their content and are not liable for any damage or loss arising from their use.</li>
                <li><strong>User-Generated Content.</strong> Opinions expressed by Users (e.g., forum posts, reviews, portfolios) are their own and do not reflect Wonegig's views. We make no representations regarding accuracy, legality, or decency of such content.</li>
            </ul>

            <h2>5. Accuracy &amp; Completeness</h2>
            <ul class="list-disc pl-6">
                <li><strong>"As-Is" Basis.</strong> The Platform and all information therein are provided "as is" and "as available," without warranty of any kind, express or implied, including but not limited to accuracy, reliability, merchantability, fitness for a particular purpose, non-infringement, or uninterrupted availability.</li>
                <li><strong>No Duty to Update.</strong> Content may become outdated; Wonegig has no obligation to correct or update historical information.</li>
            </ul>

            <h2>6. Limitation of Liability</h2>
            <p>To the maximum extent permitted by applicable law, Wonegig, its officers, directors, employees, agents, and licensors shall not be liable for any direct, indirect, incidental, consequential, punitive, or special damages (including lost profits, lost data, or business interruption) arising out of or relating to:</p>
            <ol class="list-decimal pl-6">
                <li>Use of, or inability to use, the Platform;</li>
                <li>Transactions or relationships between Users;</li>
                <li>Errors or omissions in any content;</li>
                <li>Viruses or malicious code transmitted via the Platform or third-party sites.</li>
            </ol>
            <p>Where liability cannot be disclaimed under mandatory law, it is limited to the greater of USD $200 or the total fees you paid to Wonegig in the preceding six (6) months.</p>

            <h2>7. Downloads &amp; Malware</h2>
            <p>While we scan files for viruses, you assume all risk in downloading, installing, or using any file or software obtained via Wonegig. Always apply up-to-date antivirus and backup measures.</p>

            <h2>8. Affiliate &amp; Advertising Disclosure</h2>
            <p>Wonegig may participate in affiliate programs or display paid advertisements. We may receive compensation when you click or make purchases through certain links. Such relationships do not influence our editorial independence.</p>

            <h2>9. Indemnity</h2>
            <p>You agree to indemnify and hold harmless Wonegig and its affiliates from any claim or demand (including reasonable legal fees) arising out of your: (a) breach of this Disclaimer or the Terms &amp; Conditions; (b) misuse of the Platform; or (c) violation of any law or third-party right.</p>

            <h2>10. Changes to This Disclaimer</h2>
            <p>We may revise this Disclaimer to reflect regulatory changes or operational updates. Material changes will be highlighted on the Platform 14 days before taking effect. Continued use after the effective date constitutes acceptance.</p>
        </div>
    </section>
</div>

@push('styles')
<style>
    .policy-hero-bg {
        background: linear-gradient(135deg, #0f2447 0%, #0d47a1 100%);
        position: relative;
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .policy-hero-bg::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url('https://images.unsplash.com/photo-1465101178521-c1a9136a3b99?auto=format&fit=crop&w=1500&q=80');
        background-size: cover;
        background-position: center;
        opacity: 0.12;
        z-index: 0;
    }
    .policy-hero-content {
        position: relative;
        z-index: 1;
    }
    .policy-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .policy-content p, .policy-content ul, .policy-content ol {
        margin-bottom: 1.25rem;
        line-height: 1.8;
    }
    .policy-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }
    .policy-content th, .policy-content td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        text-align: left;
    }
    .policy-content th {
        background: #f3f4f6;
        font-weight: 600;
    }
    .policy-content .text-primary {
        color: #0d47a1;
    }
</style>
@endpush
