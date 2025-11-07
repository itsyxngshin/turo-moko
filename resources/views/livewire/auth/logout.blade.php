<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
   x-data
   @click.prevent="
       fetch('{{ route('auth.logout') }}', {
           method: 'POST',
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           }
       }).then(() => window.location.href = '{{ route('auth.login') }}')
   ">
   Logout
</a>