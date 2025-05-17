<x-app-layout>
  <div class="container">

    <h1 class="page-title">Settings</h1>

    <section class="settings-section">
      <h2>Profile Settings</h2>
      <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>

        <button type="submit" class="btn-save">Save Changes</button>
      </form>
    </section>

    <section class="settings-section">
      <h2>Change Password</h2>
      <form method="POST" action="{{ route('settings.password') }}">
        @csrf

        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="new_password_confirmation">Confirm New Password</label>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>

        <button type="submit" class="btn-save">Update Password</button>
      </form>
    </section>

  </div>

  <style>
    .container {
      max-width: 700px;
      margin: auto;
      padding: 2rem;
      background: rgba(10,10,10,0.9);
      border-radius: 10px;
      box-shadow: 0 0 20px #00f7ff88;
      font-family: 'Rajdhani', sans-serif;
      color: #00f7ff;
    }

    .page-title {
      font-family: 'Orbitron', sans-serif;
      font-weight: 900;
      font-size: 2.5rem;
      text-align: center;
      margin-bottom: 2rem;
      text-shadow: 0 0 15px #00f7ffaa;
    }

    .settings-section {
      background: #111;
      padding: 1.5rem 2rem;
      border-radius: 10px;
      box-shadow: 0 0 15px #00f7ff88;
      margin-bottom: 2rem;
    }

    .settings-section h2 {
      font-family: 'Orbitron', sans-serif;
      font-weight: 700;
      font-size: 1.6rem;
      margin-bottom: 1rem;
      text-shadow: 0 0 10px #00f7ffaa;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.3rem;
      font-size: 1rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 1.2rem;
      background: #222;
      border: 1px solid #00f7ff66;
      border-radius: 6px;
      color: #00f7ff;
      font-size: 1rem;
      font-family: monospace;
      box-shadow: inset 0 0 5px #00f7ff44;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #00f7ff;
      outline: none;
      box-shadow: 0 0 10px #00f7ffcc;
    }

    .btn-save {
      background-color: #00f7ff;
      color: #111;
      font-weight: 700;
      padding: 12px 24px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-size: 1.2rem;
      box-shadow: 0 0 15px #00f7ffaa;
      transition: background-color 0.25s ease, box-shadow 0.3s ease;
      user-select: none;
      margin-top: 0.5rem;
      display: inline-block;
    }

    .btn-save:hover {
      background-color: #008c9e;
      box-shadow: 0 0 20px #008c9e;
    }
  </style>
</x-app-layout>
