<div class="form-body">
    {{--Name--}}
    <div class="form-group">
        <label class="label-control" for="name">@lang('branch.name')</label>
        <input type="text" id="name" class="form-control"
               placeholder="@lang('branch.name')" name="name"
               value="{{ old('name', isset($branch) ? $branch->name : '')}}">
        @if ($errors->has('name'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>
    {{--Phone--}}
    <div class="form-group">
        <label class="label-control" for="phone">@lang('branch.phone')</label>
        <input type="text" id="phone" class="form-control"
               placeholder="@lang('branch.phone')" name="phone"
               value="{{ old('phone', isset($branch) ? $branch->phone : '')}}">
        @if ($errors->has('phone'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('phone') }}
            </div>
        @endif
    </div>
    {{--Address--}}
    <div class="form-group">
        <label for="address">@lang('branch.address')</label>
        <textarea id="address" rows="5" class="form-control" name="address"
                  placeholder="@lang('branch.address')">{{ old('address', isset($branch) ? $branch->address : '')}}</textarea>
        @if ($errors->has('address'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('address') }}
            </div>
        @endif
    </div>
    {{--location map--}}
    <div class="row m-0">
        <label for="map_markers" style="margin-bottom: 3px">@lang('branch.location')</label>
        <div id="map_markers"
             style="position: relative; overflow: hidden; width: 100%; height: 600px; border: 1px solid #cacfe7"></div>
        <div id="info-window-content-container">
            <div id="info-window-content" style="display: none">
                <img alt="" src="" width="16" height="16" id="place-icon">
                <span id="place-name" class="title"></span><br>
                <span id="place-address"></span>
            </div>
        </div>
        <div id="search_container"></div>
    </div>

    <input type="hidden" id="lat" name="lat" @if(isset($branch) && $branch->lat) value="{{ $branch->lat }}"
           @else value="30.0331568" @endif>
    <input type="hidden" id="lng" name="lng" @if(isset($branch) && $branch->lat) value="{{ $branch->lng }}"
           @else value="31.4093249" @endif>
    <input type="hidden" id="location_url" name="location_url" @if(isset($branch) && $branch->location_url) value="{{ $branch->location_url }}"
           @else value="https://maps.google.com/?q=30.0331568,31.4093249" @endif>
</div>

@section('scripts')
    <script src="https://maps.google.com/maps/api/js?
    v=3
    &key=AIzaSyDIJ9XX2ZvRKCJcFRrl-lRanEtFUow4piM
    &libraries=places">
    </script> <!-- Google Maps API Js -->
        <script src='{{ url("/app-assets/data/gmaps/gmaps.js") }}'></script> <!-- GMaps PLugin Js -->

    <script>
        function showMap(lat, lng, address) {

            $('#search_container').html(
                '<div id="pac-card" style="display: none; background-color: white; width: 300px; margin: 10px; border: 1px solid #cacfe7">' +
                '   <div class="form-group card-body m-0" style="padding: 10px;">' +
                '       <label for="pac-input" class="label-control">@lang('map.search_title')</label>' +
                '       <input id="pac-input" class="form-control" type="text" placeholder="@lang('map.search_input_placeholder')">' +
                '   </div>' +
                '</div>'
            );

            let _lat = (lat) ? lat : 30.0331568;
            let _lng = (lng) ? lng : 31.4093249;

            let myLatLng = {lat: _lat, lng: _lng};

            let map = new google.maps.Map(document.getElementById('map_markers'), {
                zoom: 17,
                center: myLatLng,
                options: {
                    gestureHandling: 'greedy'
                }
            });

            map.setCenter(myLatLng);
            let marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: address,
                draggable: true,
            });
            marker.setPosition(myLatLng);

            google.maps.event.addListener(marker, 'dragend', function (marker) {
                let latLng = marker.latLng;
                let currentLatitude = latLng.lat();
                let currentLongitude = latLng.lng();
                $("#lat").val(currentLatitude);
                $("#lng").val(currentLongitude);

                let url = "https://maps.google.com/?q=" + currentLatitude + "," + currentLongitude;
                $('#location_url').val(url);
            });

            //===============================================
            let card = document.getElementById('pac-card');
            let input = document.getElementById('pac-input');

            if (card) {
                let card_element = $('#pac-card');
                setTimeout(function () {
                    card_element.css('display', 'block');
                }, 1000)
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
            }

            let autocomplete = new google.maps.places.Autocomplete(input);

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo('bounds', map);

            // Set the data fields to return when the user selects a place.
            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);

            let infoWindow = new google.maps.InfoWindow();
            let infoWindowContent = document.getElementById('info-window-content');
            infoWindow.setContent(infoWindowContent);
            // let search_marker = new google.maps.Marker({
            //   map: map,
            //   anchorPoint: new google.maps.Point(0, -29)
            // });

            autocomplete.addListener('place_changed', function () {
                $('#info-window-content').css('display', 'block');
                infoWindow.close();
                marker.setVisible(false);
                let place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }
                $("#lat").val(place.geometry.location.lat());
                $("#lng").val(place.geometry.location.lng());
                let url = "https://maps.google.com/?q=" + place.geometry.location.lat() + "," + place.geometry.location.lng();
                $('#location_url').val(url);

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                let address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infoWindowContent.children['place-icon'].src = place.icon;
                infoWindowContent.children['place-name'].textContent = place.name;
                infoWindowContent.children['place-address'].textContent = address;
                infoWindow.open(map, marker);

                $('#info-window-content-container').html(
                    '<div id="info-window-content" style="display: none">' +
                    '<img alt="" src="" width="16" height="16" id="place-icon">' +
                    '<span id="place-name" class="title"></span><br>' +
                    '<span id="place-address"></span>' +
                    '</div>'
                );
            });

            return map;
            //===============================================
        }
    </script>

    <script>
        $(document).ready(function () {
            let lat = $("#lat").val();
            let lng = $("#lng").val();
            showMap(parseFloat(lat), parseFloat(lng), "branch");

            $(window).keydown(function (event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    return false;
                }
            });
        })
    </script>

    {{--  Disable Google Maps API Key  --}}
    <script>
        (() => {
            "use strict";
            // Store old reference
            const appendChild = Element.prototype.appendChild;

            // All services to catch
            const urlCatchers = [
                "/AuthenticationService.Authenticate?",
                "/QuotaService.RecordEvent?"
            ];

            // Google Map is using JSONP.
            // So we only need to detect the services removing access and disabling them by not
            // inserting them inside the DOM
            Element.prototype.appendChild = function (element) {
                const isGMapScript = element.tagName === 'SCRIPT' && /maps\.googleapis\.com/i.test(element.src);
                const isGMapAccessScript = isGMapScript && urlCatchers.some(url => element.src.includes(url));

                if (!isGMapAccessScript) {
                    return appendChild.call(this, element);
                }

                // Extract the callback to call it with success data
                // Only needed if you actually want to use Autocomplete/SearchBox API
                const callback = element.src.split(/.*callback=([^\&]+)/, 2).pop();
                const [a, b] = callback.split('.');
                window[a][b]([1, null, 0, null, null, [1]]);

                // Returns the element to be compliant with the appendChild API
                return element;
            };

        })();
    </script>
@endsection
