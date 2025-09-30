import Auth from './Auth'
import RenderRssFeedController from './RenderRssFeedController'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    RenderRssFeedController: Object.assign(RenderRssFeedController, RenderRssFeedController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers