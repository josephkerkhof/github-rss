import Fortify from './Fortify'
import Sanctum from './Sanctum'
import Telescope from './Telescope'

const Laravel = {
    Fortify: Object.assign(Fortify, Fortify),
    Sanctum: Object.assign(Sanctum, Sanctum),
    Telescope: Object.assign(Telescope, Telescope),
}

export default Laravel