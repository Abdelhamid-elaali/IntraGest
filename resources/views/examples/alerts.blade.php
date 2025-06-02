@extends('layouts.app')

@section('title', 'Alert Examples')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Alert Component Examples</h1>
        
        <div class="space-y-6">
            <x-alert type="info" :auto-dismiss="false" class="mb-4">
                <strong>Info Alert:</strong> This is an information message using the blue color scheme.
            </x-alert>

            <x-alert type="success" :auto-dismiss="false" class="mb-4">
                <strong>Success Alert:</strong> This is a success message using the blue color scheme.
            </x-alert>

            <x-alert type="warning" :auto-dismiss="false" class="mb-4">
                <strong>Warning Alert:</strong> This is a warning message using the yellow color scheme.
            </x-alert>

            <x-alert type="error" :auto-dismiss="false" class="mb-4">
                <strong>Error Alert:</strong> This is an error message using the red color scheme.
            </x-alert>

            <x-alert type="secondary" :auto-dismiss="false" class="mb-4">
                <strong>Secondary Alert:</strong> This is a secondary message using the orange color scheme.
            </x-alert>

            <x-alert type="primary" :auto-dismiss="false" class="mb-4">
                <strong>Primary Alert:</strong> This is a primary message using the blue color scheme.
            </x-alert>

            <h2 class="text-xl font-bold mt-8 mb-4">Auto-dismissing Alerts</h2>

            <x-alert type="info" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
                <strong>Auto-dismiss Info:</strong> This alert will automatically dismiss after 4 seconds.
            </x-alert>

            <h2 class="text-xl font-bold mt-8 mb-4">Dismissible Alerts</h2>

            <x-alert type="success" :dismissible="true" class="mb-4">
                <strong>Dismissible Success:</strong> Click the X to dismiss this alert.
            </x-alert>

            <h2 class="text-xl font-bold mt-8 mb-4">Alerts with Titles</h2>

            <x-alert type="warning" :dismissible="true" title="Important Warning" class="mb-4">
                This is an alert with a title and dismissible button.
            </x-alert>
        </div>
    </div>
</div>
@endsection
