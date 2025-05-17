<x-app-layout>
  <div class="monitoring-content" style="max-width: 700px; margin: auto; padding: 1rem;">

    <h1 class="page-title" style="color: #00f7ff; font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 2.5rem; text-align: center; margin-bottom: 1.5rem;">
      Face Detection Monitoring
    </h1>

    <div class="buttons" style="margin-bottom: 1.5rem; text-align: center;">
      <a href="{{ route('monitoring.daily') }}" class="btn">Daily</a>
      <a href="{{ route('monitoring.weekly') }}" class="btn">Weekly</a>
      <a href="{{ route('monitoring.monthly') }}" class="btn">Monthly</a>
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
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage" />
    <div id="caption"></div>
    </div>

    <div style="text-align: center; margin-top: 1.5rem;">
      {{ $imageUrls->links() }}
    </div>
  </div>
  <script>
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
    .btn {
      background-color: #00f7ff;
      color: #111;
      padding: 8px 16px;
      margin: 0 6px;
      border-radius: 6px;
      font-weight: 700;
      text-transform: uppercase;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.25s ease;
      box-shadow: 0 0 8px #00f7ffaa;
      user-select: none;
    }
    .btn:hover {
      background-color: #008c9e;
      box-shadow: 0 0 14px #008c9e;
    }
    .buttons a {
      position: relative;
      z-index: 9999;
    }
    
    .monitoring-content {
    position: relative !important; /* enable z-index */
    z-index: 10 !important;        /* higher than canvas */
    background-color: rgba(10,10,10,0.9) !important; /* slightly opaque */
    padding: 1rem !important;
    border-radius: 10px !important;
    max-width: 700px !important;
    margin: auto !important;
    }
    #bgCanvas {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 0 !important;           /* lowest layer */
    pointer-events: none !important; /* don't block clicks */
    }
   
  /* The Modal (background) */
  .modal {
    display: none; 
    position: fixed; 
    z-index: 10000; 
    padding-top: 60px; 
    left: 0; top: 0; width: 100%; height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.9); 
    
  }

  /* Modal Content (image) */
  .modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80vh;
    border-radius: 10px;
    box-shadow: 0 0 20px #00f7ff;
  }

  /* Caption */
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

  /* Close button */
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
  font-family: monospace;
  font-size: 1.1rem; /* mas malaki ang font size */
}

/* Pagination list items */
nav[aria-label="Pagination Navigation"] ul {
  display: flex;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
}

/* Pagination items (buttons/links) */
nav[aria-label="Pagination Navigation"] li {
  border: 2px solid #00f7ff; /* mas makapal ang border */
  border-radius: 6px;
  padding: 8px 14px; /* mas malaki ang padding */
  font-weight: bold;
  min-width: 40px; /* fixed min width para consistent ang size */
  text-align: center;
  user-select: none;
}

/* Active page */
nav[aria-label="Pagination Navigation"] li.active span {
  background-color: #00f7ff;
  color: #111;
  pointer-events: none;
}

/* Disabled links */
nav[aria-label="Pagination Navigation"] li.disabled span {
  color: #555;
  border-color: #555;
  pointer-events: none;
}

/* Links styling */
nav[aria-label="Pagination Navigation"] li a {
  color: #00f7ff;
  text-decoration: none;
  display: inline-block;
  transition: background-color 0.3s ease;
}

nav[aria-label="Pagination Navigation"] li a:hover {
  background-color: #008c9e;
  color: #fff;
}


  </style>
</x-app-layout>
