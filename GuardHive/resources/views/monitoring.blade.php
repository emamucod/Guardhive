<x-app-layout>
  <div class="monitoring-content" style="max-width: 700px; margin: auto; padding: 1rem;">

    <h1 class="page-title" style="color: #00f7ff; font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 2.5rem; text-align: center; margin-bottom: 1.5rem;">
      Face Detection Monitoring
    </h1>

    <div class="buttons">
      <a href="{{ route('monitoring.daily') }}" class="btn btn-primary {{ request()->routeIs('monitoring.daily') ? 'active' : '' }}">Daily</a>
      <a href="{{ route('monitoring.weekly') }}" class="btn btn-primary {{ request()->routeIs('monitoring.weekly') ? 'active' : '' }}">Weekly</a>
      <a href="{{ route('monitoring.monthly') }}" class="btn btn-primary {{ request()->routeIs('monitoring.monthly') ? 'active' : '' }}">Monthly</a>
    </div>

    <!-- Print Button -->
    <div style="text-align: center; margin-bottom: 1rem;">
      <button id="printBtn" class="btn">Print Report</button>
    </div>

    <div class="table-container" style="overflow-x: auto; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 247, 255, 0.3);">
      <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
        <thead>
          <tr style="background-color: transparent; color: #00f7ff;">
            <th style="padding: 10px 15px; text-align: left;">Image</th>
            <th style="padding: 10px 15px; text-align: left;">Timestamp</th>
          </tr>
        </thead>
        <tbody>
          @foreach($imageUrls as $imageUrl)
            <tr style="background-color: #111; border-radius: 10px; box-shadow: inset 0 0 10px #00f7ff;">
              <td style="padding: 12px; border: 1px solid #00f7ff; background-color: #111;">
                <a href="{{ $imageUrl['url'] }}" class="image-link" onclick="openModal(event)">
                  <img src="{{ $imageUrl['url'] }}" alt="Detected Movement" style="max-width: 120px; border-radius: 8px; box-shadow: 0 0 8px #00f7ff;" />
                </a>
              </td>

              <td style="padding: 10px 15px; color: #00f7ff; vertical-align: middle; font-family: monospace;">
                {{ $imageUrl['date']->format('Y-m-d H:i:s') }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div id="imgModal" class="modal" onclick="closeModal()">
      <span class="close" onclick="closeModal()">&times;</span>
      <img class="modal-content" id="modalImage" />
      <div id="caption"></div>
    </div>

    <div style="text-align: center; margin-top: 1.5rem;">
      {{ $imageUrls->links('pagination::simple-tailwind') }}
    </div>
  </div>

  <script>
    document.getElementById('printBtn').addEventListener('click', function() {
      window.print();
    });

    function openModal(event) {
      event.preventDefault();
      const modal = document.getElementById("imgModal");
      const modalImg = document.getElementById("modalImage");
      const caption = document.getElementById("caption");

      modal.style.display = "block";
      modalImg.src = event.currentTarget.href;
      caption.textContent = event.currentTarget.querySelector('img').alt;
    }

    function closeModal() {
      document.getElementById("imgModal").style.display = "none";
    }
  </script>

  <style>
    /* Container styling */
    .monitoring-content {
      position: relative !important;
      z-index: 10 !important;
      background-color: rgba(10,10,10,0.9) !important;
      padding: 1rem !important;
      border-radius: 10px !important;
      max-width: 700px !important;
      margin: auto !important;
    }

    /* Buttons container */
    .buttons {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-bottom: 1.5rem;
    }

    /* Button base */
    .btn {
      padding: 10px 24px;
      border-radius: 30px;
      font-weight: 700;
      text-transform: uppercase;
      text-decoration: none;
      cursor: pointer;
      font-family: 'Rajdhani', sans-serif;
      font-size: 1rem;
      transition: all 0.3s ease;
      user-select: none;
      box-shadow: 0 4px 15px rgba(0, 247, 255, 0.25);
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #111;
      background: linear-gradient(135deg, #00f7ff, #008c9e);
    }

    /* Hover & focus */
    .btn:hover,
    .btn:focus {
      background: linear-gradient(135deg, #00d4e3, #007277);
      box-shadow: 0 6px 20px rgba(0, 199, 255, 0.5);
      outline: none;
    }

    /* Active button styling */
    .btn.active {
      background: #00a9b8;
      color: #fff;
      box-shadow: 0 6px 25px rgba(0, 169, 184, 0.7);
      pointer-events: none;
    }

    /* Modal background */
    .modal {
      display: none;
      position: fixed;
      z-index: 10000;
      padding-top: 60px;
      left: 0; top: 0; width: 100%; height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.9);
    }

    /* Modal image */
    .modal-content {
      margin: auto;
      display: block;
      max-width: 80%;
      max-height: 80vh;
      border-radius: 10px;
      box-shadow: 0 0 20px #00f7ff;
    }

    /* Caption text */
    #caption {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
      text-align: center;
      color: #00f7ff;
      padding: 10px 0;
      font-family: monospace;
    }

    /* Modal close button */
    .close {
      position: absolute;
      top: 25px;
      right: 35px;
      color: #00f7ff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
      user-select: none;
    }

    /* Pagination container */
    nav[aria-label="Pagination Navigation"] {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
      font-family: 'Rajdhani', sans-serif;
      font-size: 1.1rem;
      gap: 10px;
    }

    /* Pagination list */
    nav[aria-label="Pagination Navigation"] ul {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      gap: 8px;
    }

    /* Pagination items */
    nav[aria-label="Pagination Navigation"] li {
      border: none;
    }

    /* Pagination links & spans */
    nav[aria-label="Pagination Navigation"] li a,
    nav[aria-label="Pagination Navigation"] li span {
      padding: 8px 16px;
      border-radius: 30px;
      font-weight: 700;
      color: #111;
      background: linear-gradient(135deg, #00f7ff, #008c9e);
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      user-select: none;
      box-shadow: 0 4px 15px rgba(0, 247, 255, 0.25);
    }

    /* Hover for pagination links */
    nav[aria-label="Pagination Navigation"] li a:hover {
      background: linear-gradient(135deg, #00d4e3, #007277);
      box-shadow: 0 6px 20px rgba(0, 199, 255, 0.5);
    }

    /* Active page styling */
    nav[aria-label="Pagination Navigation"] li.active span {
      background: #00a9b8;
      color: #fff;
      box-shadow: 0 6px 25px rgba(0, 169, 184, 0.7);
      pointer-events: none;
    }

    /* Disabled page styling */
    nav[aria-label="Pagination Navigation"] li.disabled span {
      background: #ccc;
      color: #666;
      pointer-events: none;
      box-shadow: none;
    }

    /* Print media styles */
    @media print {
  body * {
    visibility: hidden;
  }
  .monitoring-content, .monitoring-content * {
    visibility: visible;
  }
  .monitoring-content {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    margin: 0;
    padding: 0;
    box-shadow: none !important;
    background: #fff !important;
    color: #000 !important;
  }
  
  /* Remove shadows and glows from table rows and cells */
  .monitoring-content table tr,
  .monitoring-content table td,
  .monitoring-content table th {
    box-shadow: none !important;
    border: 1px solid #000 !important;
    background: #fff !important;
    color: #000 !important;
  }

  /* Images: show fully, scale if needed */
  .monitoring-content table img {
    max-width: 100% !important;
    height: auto !important;
    box-shadow: none !important;
    border-radius: 0 !important;
  }

  /* Hide buttons, pagination, modal */
  #printBtn, .buttons, nav[aria-label="Pagination Navigation"], .modal {
    display: none !important;
  }
}

  </style>
</x-app-layout>
