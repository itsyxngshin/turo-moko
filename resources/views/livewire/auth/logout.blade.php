<a
    href="#"
    x-data
    @click.prevent="if (confirm('Are you sure you want to logout?')) $wire.logout()"
    class="bg-red-500 text-white px-3 py-1 rounded"
>
    Logout
</a>