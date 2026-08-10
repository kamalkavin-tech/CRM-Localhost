<x-guest-layout 
    :title="'Terms of Service - ' . config('app.name')"
    description="Localhost Terms of Service - Use of the Localhost website and all related services is subject to the following terms of service."
    :ogTitle="'Terms of Service - ' . config('app.name')"
    ogDescription="Read the Terms of Service for Localhost. Learn about the rules and guidelines for using our open-source CRM platform.">
    <x-legal-document
        title="Terms of Service"
        subtitle="Use of the Localhost website and all related services is subject to the following terms of service."
        :content="$terms"
    />
</x-guest-layout>
