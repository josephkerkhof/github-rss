import RepositoryController from './RepositoryController'
import BranchController from './BranchController'
import PullRequestController from './PullRequestController'

const Controllers = {
    RepositoryController: Object.assign(RepositoryController, RepositoryController),
    BranchController: Object.assign(BranchController, BranchController),
    PullRequestController: Object.assign(PullRequestController, PullRequestController),
}

export default Controllers