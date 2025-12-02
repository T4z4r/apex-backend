<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Policy::create([
            'type' => 'privacy_policy',
            'title' => 'Privacy Policy',
            'content' => '<h2>1. Information We Collect</h2>
<p>We collect information you provide directly to us, such as when you create an account, use our services, or contact us for support.</p>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>

<h2>3. Information Sharing</h2>
<p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>

<h2>4. Data Security</h2>
<p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

<h2>5. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at privacy@example.com</p>',
            'is_active' => true,
        ]);

        \App\Models\Policy::create([
            'type' => 'terms_of_service',
            'title' => 'Terms of Service',
            'content' => '<h2>1. Acceptance of Terms</h2>
<p>By accessing and using our service, you accept and agree to be bound by the terms and provision of this agreement.</p>

<h2>2. Use License</h2>
<p>Permission is granted to temporarily use our service for personal, non-commercial transitory viewing only.</p>

<h2>3. User Responsibilities</h2>
<p>You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer.</p>

<h2>4. Prohibited Uses</h2>
<p>You may not use our services for any illegal or unauthorized purpose nor may you violate any laws in your jurisdiction.</p>

<h2>5. Termination</h2>
<p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever.</p>

<h2>6. Contact Information</h2>
<p>If you have any questions about these Terms of Service, please contact us at legal@example.com</p>',
            'is_active' => true,
        ]);
    }
}
