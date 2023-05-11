import 'ol/ol.css';
import Map from 'ol/Map';
import View from 'ol/View';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import Feature from 'ol/Feature';
import Point from 'ol/geom/Point';
import {fromLonLat} from 'ol/proj';
import {Icon, Style} from 'ol/style';

// create a map object
const map = new Map({
  target: 'map',
  layers: [
    new TileLayer({
      source: new OSM()
    })
  ],
  view: new View({
    center: fromLonLat([0, 0]),
    zoom: 2
  })
});

// add a marker for each location
const locations = [
  {name: 'Location 1', lat: 0, lon: 0},
  {name: 'Location 2', lat: 10, lon: 10},
  {name: 'Location 3', lat: -10, lon: -10},
];
locations.forEach(location => {
  const marker = new Feature({
    geometry: new Point(fromLonLat([location.lon, location.lat])),
    name: location.name
  });
  marker.setStyle(new Style({
    image: new Icon({
      src: 'https://openlayers.org/en/latest/examples/data/icon.png',
      scale: 0.1
    })
  }));
  map.addLayer(new VectorLayer({
    source: new VectorSource({
      features: [marker]
    })
  }));
});
